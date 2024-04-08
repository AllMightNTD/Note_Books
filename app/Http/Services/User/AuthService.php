<?php

namespace App\Http\Services\User;

use App\Http\Requests\User\LoginRequest;
use App\Http\Services\BaseService;
use App\Jobs\SendEmailJob;
use App\Models\Admin;
use App\Models\User;
use App\Repositories\Interfaces\AuthInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthService extends BaseService
{
    protected $auth;
    const chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789/*-+.,!#$%&()~|_';

    public function __construct(AuthInterface $auth)
    {
        $this->auth = $auth;
    }

    public function setModel()
    {
        $this->model = new User();
    }

    public function index(Request $request){
        $user = $this -> auth -> index($request);
        
        return [
            'data' => $user -> items(),
            'total' => $user -> total()
        ];
    }

    public function applySorting(Request $request){
        $sortBy = $request -> get('sortBy');
        $orderBy = $request -> get('orderyBy') ?? 'asc';
        switch ($sortBy) {
            case 'name':
                $this->query->orderByRaw("name $orderBy");
                break;
            case 'email':
                $this->query->orderByRaw("name $orderBy");
                break;
            case 'tel':
                $this->query->orderByRaw("tel $orderBy");
                break;
            default:
                $this->query->orderByRaw("created_at , desc");
                break;
       
        }
    }

    public function register(Request $request)
    {
        DB::beginTransaction();
        try {
            $this->auth->register($request);
            DB::commit();
            return response()->json(['message' => 'Successfully register']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Hệ thống đang bảo trì'], 500);
        }
    }

    public function login(LoginRequest $request)
    {
        $user = User::query()->where('email' , $request -> email) -> where('is_valid' , 1 ) -> first();
        if(!$user){
            return response()->json(['message' => 'User not valid'], 401);
        }
        $credentials = $request->only('email', 'password');

        if (!$token = auth('api')->attempt($credentials)) {
            return response()->json(['message' => 'Wrong login name or password'], 401);
        }
        $refreshToken = $this->createRefreshToken();
        return $this->respondWithToken($token, $refreshToken);
    }

    public function me(Request $request)
    {
        try {
            $user = auth('api')->user();
            if($user){
                $user = $this -> auth -> show($request , $user -> id);
                return [
                    'data' => $user
                ];
            }
        } catch (JWTException $e) {
            return response()->json(['message' => 'User invalid'], 500);
        }
    }

    public function respondWithToken($token, $refreshToken)
    {
        return response()->json([
            'access_token' => $token,
            'refresh_token' => $refreshToken,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL()
        ]);
    }

    public function createRefreshToken()
    {

        $data = [
            'user_id' => auth('api')->user()->id,
            'random' => rand() . time(),
            'exp' => time() + config('jwt.refresh_ttl')
        ];
        return JWTAuth::getJWTProvider()->encode($data);
    }

    public function refreshToken(){
        $refreshToken = request() -> refresh_token;
        try {
            $decoded = JWTAuth::getJWTProvider()->decode($refreshToken);
            $user = User::find($decoded['user_id']);
            
            if(!$user){
                response()->json(['message' => 'User not found'], 404);
            }

            // Cần truyền vào access_token hiện tại vào Bearer...
            auth('api') -> invalidate(true);
            $token = auth('api')->login($user); // Tạo token mới
            $refreshToken = $this->createRefreshToken();

            return $this -> respondWithToken($token , $refreshToken);
        } catch (JWTException $e) {
            return response()->json(['message' => 'User invalid'], 500);
        }
    }

    public function resetPassword(Request $request){
        $email = $request -> input('email');
        $user = User::query()->where('email', $email)->where('is_valid',1)->first();
        
        if(!$user){
            return response()->json(['message' => 'Not found'], 404);
        }

        DB::beginTransaction();
        try {
            $password = $this -> makeRandStr();
            $user -> password =  bcrypt($password);
            $user -> updated_at =  date('Y-m-d H:i:s');
            $user -> save();
            $data = [
                'password' => $password
            ];
            SendEmailJob::dispatch($user , $data);
            
            DB::commit();
            return [
                'data' => []
            ];
        } catch (\Throwable $th) {
            throw $th;
            DB::rollBack();
        }
    }

    // Randome password
    public function makeRandStr($length = 8)
    {
        $charNum = strlen(self::chars);
        $password = '';
        for ($i = 0; $i < $length; $i++) {
            $password .= self::chars[mt_rand(0, $charNum - 1)];
        }
        return $password;
    }

    public function destroy(Request $request, $id, $isForceDelete = false)
    {
        $user = User::query()
            ->where('id', $id)
            ->update([
                'is_valid' => 0,
                'updated_at' => date('Y-m-d H:i:s')
            ]);

        return [
            'message' => 'Delete user successfully',
            'data' => [],
        ];
    }

}

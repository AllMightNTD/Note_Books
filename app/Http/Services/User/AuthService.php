<?php

namespace App\Http\Services\User;

use App\Http\Requests\User\InformationRequest;
use App\Http\Requests\User\LoginRequest;
use App\Http\Services\BaseService;
use App\Jobs\SendEmailJob;
use App\Models\Information;
use App\Models\User;
use App\Repositories\Interfaces\AuthInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
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
        $credentials = $request->only('email', 'password');

        if (!$token = auth('api')->attempt($credentials)) {
            return $this -> errorResponse('Mật khẩu không đúng' , 403);
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
            'data' => [
                'access_token' => $token,
                'refresh_token' => $refreshToken,
                'token_type' => 'bearer',
                'expires_in' => auth('api')->factory()->getTTL()
            ]
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

            $token = auth('api')->login($user); // Tạo token mới
            $refreshToken = $this->createRefreshToken();

            return $this -> respondWithToken($token , $refreshToken);
        } catch (JWTException $e) {
            Log::info($e -> getMessage());
            return response()->json(['message' => 'User invalid'], 500);
        }
    }

    public function resetPassword(Request $request){
        $email = $request -> input('email');
        $user = User::query()->where('email', $email)->first();
        
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

    public function information(Request $request){
        $id = auth('api') -> user() -> id;
        return [
            'data' => $this -> auth -> show($request , $id)
        ];
    }

    public function updateInformation(Request $request)
    {
        $user = auth('api') -> user();
        $id = auth('api') -> user() -> id;
        
        DB::beginTransaction();
        try {
          
        $information = Information::updateOrCreate(
            ['user_id' => $id], // Đúng cú pháp mảng
            [
                'sex' => $request->get('sex') ?? 0,
                'birth_day' => $request->get('birth_day')
            ]
        );
        $user -> name = $request->name;
        $user -> email = $request -> email;
        $user -> save();

        DB::commit();
        
        return [
            'data' => $information
        ];
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
        }
    }

    public function changePassword(Request $request){
        $request->validate([
            'old_password' => 'required',
        ]);

        $user = auth('api') -> user();

        if (!Hash::check($request->old_password, $user->password)) {
            throw ValidationException::withMessages([
                'old_password' => ['Mật khẩu cũ không đúng']
            ]);
        }
        $newPassword = Hash::make($request -> new_password);

        DB::beginTransaction();
        try {

            $user -> password = $newPassword;
            $user -> save();

            DB::commit();

            return [
                'data' => [],
                'message' => 'Cập nhật mật khẩu thành công'
            ];
        } catch (\Exception $e) {
            Log::info($e -> getMessage());
            return $this -> errorResponse();
        }
    }

}

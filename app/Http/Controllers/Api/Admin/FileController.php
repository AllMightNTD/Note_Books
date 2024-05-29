<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\BaseController;
use Illuminate\Http\Request;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class FileController extends BaseController
{
    public function __construct()
    {
        
    }

    public function index(Request $request){
        return 1;
    }

    public function store(Request $request)
    {
        $uploadedFileUrl = Cloudinary::upload($request->file('file')->getRealPath())->getSecurePath();

        // Tách phần URL cần thiết
        $urlPath = parse_url($uploadedFileUrl, PHP_URL_PATH);
        $relativePath = ltrim($urlPath, '/');

        return response()->json(['url' => $relativePath]);
    }

    public function update(Request $request, $id)
    {
        return 2;
    }
}

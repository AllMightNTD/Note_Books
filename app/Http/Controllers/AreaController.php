<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Controller;
use App\Models\Admin\Area;
use Illuminate\Http\Request;

class AreaController extends BaseController
{
    //
    public function index(Request $request)
    {
        $areas = Area::all();
        $formattedCategories = [];

        if ($areas->isNotEmpty()) {
            foreach ($areas as $subCategory) {
                $formattedCategories[] = [
                    "label" => $subCategory->name,
                    "value" => $subCategory->id
                ];
            }
        }

        return [
            'data' =>  $formattedCategories
        ];
    }
}

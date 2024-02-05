<?php

namespace App\Repositories\Repository;

use App\Repositories\Interfaces\BaseInterface;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

class BaseRepository implements BaseInterface
{
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function show(Request $request, $id)
    {
        $this->model->find($id);
    }
}

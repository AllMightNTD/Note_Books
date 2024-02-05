<?php

namespace App\Http\Services;

use Illuminate\Http\Request;

abstract class BaseService
{
    protected $model;
    protected $query;
    protected $request;

    public function __construct()
    {
        $this->request = request();
        $this->setModel();
        $this->setQuery();
    }

    abstract protected function setModel();

    protected function setQuery()
    {
        $this->query = $this->model->query();
    }

    public function show(Request $request, $id)
    {
        $item = $this->query->find($id);
        if (!$item) {
            return response()->json([
                'message' => __('message.not_found'),
            ], 404);
        }

        return response()->json(
            [
                'data' => $item
            ]
        );
    }

    public function destroy(Request $request, $id, $isForceDelete = false)
    {

        if ($isForceDelete) {
            $this->_forceDelete($request, $id);
        } else {
            $this->_softDelete($request, $id);
        }

        return response()->json(['message' => __('message.delete_success')]);
    }

    private function _softDelete(Request $request, $id)
    {
        $model = $this->query->findOrFail($id);
        $model->delete();
    }

    private function _forceDelete(Request $request, $id)
    {
        $model = $this->query->withTrashed()->findOrFail($id);
        $model->forceDelete();
    }

    public function errorResponse($message = 'Hệ thống đang bảo trì !!!')
    {
        return response()
            ->json([
                'message' => $message,
            ], 500);
    }
}

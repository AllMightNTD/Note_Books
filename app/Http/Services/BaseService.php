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
                'message' => 'Not found',
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

        return response()->json(['message' => 'Delete success']);
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

    public function errorResponse($message = 'The system is maintenance !!!')
    {
        return response()
            ->json([
                'message' => $message,
            ], 500);
    }

    public function index(Request $request)
    {
        if (method_exists($this, 'applySorting')) {
            $this->applySorting();
        }
        if (method_exists($this, 'applyFilter')) {
            $this->applyFilter();
        }
        $data = $this->addDefaultFilter();
        if (method_exists($this, 'setTransformers') && $request->per_page != -1) {
            $data = $this->setTransformers($data);
        }

        return response()->json($data);
    }

    protected function addDefaultFilter()
    {
        $data = $this->request->all();
        $table = $this->model->getTable();

        foreach ($data as $key => $value) {
            if ($value || $value === '0') {
                try {
                    if (strpos($key, ':') !== false) {
                        $field = str_replace(':', '.', $key);
                        $query = $this->query;
                        if (preg_match('/(.*)_like$/', $field, $matches)) {
                            $relations = explode('.', $matches[1]);
                            if (count($relations) == 3) {
                                $query->whereHas(
                                    $relations[0],
                                    function ($query) use ($relations, $value) {
                                        $query->whereHas($relations[1], function ($query) use ($relations, $value) {
                                            $query->where($relations[2], 'like', "%$value%");
                                        });
                                    }
                                );
                            } else {
                                $query->whereHas(
                                    $relations[0],
                                    function ($query) use ($relations, $value) {
                                        $query->where($relations[1], 'like', "%$value%");
                                    }
                                );
                            }
                        }

                        if (preg_match('/(.*)_equal$/', $field, $matches)) {
                            $relations = explode('.', $matches[1]);
                            if (count($relations) == 3) {
                                $query->whereHas(
                                    $relations[0],
                                    function ($query) use ($relations, $value) {
                                        $query->whereHas($relations[1], function ($query) use ($relations, $value) {
                                            $query->where($relations[2], '=', $value);
                                        });
                                    }
                                );
                            } else {
                                $query->whereHas(
                                    $relations[0],
                                    function ($query) use ($relations, $value) {
                                        $query->where($relations[1], '=', $value);
                                    }
                                );
                            }
                        }
                    } else {
                        if (preg_match('/(.*)_like$/', $key, $matches)) {
                            if (config('database.default') === 'sqlsrv') {
                                $this->query->where($table . '.' . $matches[1], 'like', "%$value%");
                            } else {
                                $this->query->where($table . '.' . $matches[1], 'like', '%' . $value . '%');
                            }
                        }
                        if (preg_match('/(.*)_equal$/', $key, $matches)) {
                            $value = explode(',', $value);
                            if (sizeof($value) === 1) {
                                $this->query->where($table . '.' . $matches[1], $value);
                            } else {
                                $this->query->whereIn($table . '.' . $matches[1], $value);
                            }
                        }
                        if (preg_match('/(.*)_notEqual$/', $key, $matches)) {
                            $value = explode(',', $value);
                            if (sizeof($value) === 1) {
                                $this->query->where($table . '.' . $matches[1], '!=', $value);
                            } else {
                                $this->query->whereNotIn($table . '.' . $matches[1], $value);
                            }
                        }
                        if (preg_match('/(.*)_between$/', $key, $matches)) {
                            $this->query->whereBetween($table . '.' . $matches[1], $value);
                        }
                        if (preg_match('/(.*)_isnull$/', $key, $matches)) {
                            if ($value == 1) {
                                $this->query->whereNull($table . '.' . $matches[1]);
                            }
                            if ($value == 0) {
                                $this->query->whereNotNull($table . '.' . $matches[1]);
                            }
                        }
                    }

                    if (preg_match('/^has_(.*)/', $key, $matches)) {
                        if ($value) {
                            $this->query->whereHas($matches[1]);
                        } else {
                            $this->query->whereDoesntHave($matches[1]);
                        }
                    }

                    if ($key == 'only_trashed' && $value) {
                        $this->query->onlyTrashed();
                    }

                    if ($key == 'with_trashed' && $value) {
                        $this->query->withTrashed();
                    }

                    if ($key == 'select' && $value) {
                        $this->query->select($value);
                    }

                    if ($key == 'sort' && $value) {
                        $sorts = explode(',', $value);
                        $this->query->getQuery()->orders = null;
                        foreach ($sorts as $sort) {
                            $sortParams = explode('|', $sort);
                            if (strpos($sortParams[0], '.') !== false) {
                                $this->query->orderByJoin($sortParams[0], $sortParams[1] ?? 'asc');
                            } else {
                                $this->query->orderBy($table . '.' . $sortParams[0], $sortParams[1] ?? 'asc');
                            }
                        }
                    }
                } catch (\Exception $e) {
                    continue;
                }
            }
        }

        $per_page = $this->request->per_page;

        if ($per_page != -1) {
            return $this->query
                ->orderBy($this->model->getTable() . '.id', 'desc')
                ->paginate($per_page ?: 20);
        }

        return $this->query->get();
    }
}

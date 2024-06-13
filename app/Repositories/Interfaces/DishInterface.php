<?php

namespace App\Repositories\Interfaces;

interface DishInterface extends BaseInterface
{
    public function store($data);

    public function update($data, $id);

    public function allCategory();
}

<?php

namespace App\Repositories\Interfaces;

interface CategoryInterface extends BaseInterface
{
    public function store($data);

    public function update($data, $id);

    public function allCategory();
}

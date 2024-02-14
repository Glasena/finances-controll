<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;

abstract class AbstractService
{
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function findOneById($id)
    {
        return $this->model->find($id);
    }

    public function create(Model $model){
        
        return $this->model->create($model);

    }
}
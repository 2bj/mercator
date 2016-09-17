<?php

namespace App\Repositories;

use App\Entities\AbstractEntity;

abstract class AbstractRepository
{

    protected $entity;

    public function __construct(AbstractEntity $entity)
    {
        $this->entity = $entity;
    }

}
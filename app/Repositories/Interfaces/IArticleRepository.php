<?php

namespace App\Repositories\Interfaces;

interface IArticleRepository
{
    public function create($data);

    public function find($id);

    public function findWithDateTime($data);

    public function getAll($data = null);
}

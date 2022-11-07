<?php

namespace App\Repositories\Interfaces;

interface IArticleRepository
{
    public function create($data);

    public function find($id);

    public function findWithDateTime($key, $datetime);

    public function getAll($data = null);
}

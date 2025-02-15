<?php

namespace App\Repositories\Contracts;

interface BaseRepositoryInterface
{
    public function all(array $columns = [], array $relations = [], array $pluck = []);

    public function find(int|string $id, ?array $columns = null);

    public function store(array $data);

    public function update(int|string $id, array $data);

    public function delete(int|string $id);

    public function findBy(array $criteria, array $columns = [], bool $single = true);

    public function updateBy(array $criteria, array $data);

    public function paginate($perPage = null, $columns = ['*'], $pageName = 'page', $page = null);
}

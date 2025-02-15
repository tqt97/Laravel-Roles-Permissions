<?php

namespace App\Repositories\Eloquents;

use App\Repositories\Contracts\BaseRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class BaseEloquentRepository implements BaseRepositoryInterface
{
    protected $model;

    /**
     * Retrieve all records with optional columns, relations and pluck.
     */
    public function all(array $columns = [], array $relations = [], array $pluck = []): Builder|Collection
    {
        $query = $this->model::query();

        if (! empty($relations)) {
            $query->with($relations);
        }

        if (! empty($columns)) {
            return $query->get($columns);
        }

        if (! empty($pluck)) {
            return $query->get()->{$pluck['method']}($pluck['first'], $pluck['second'])->toArray();
        }

        return $query->get();
    }

    /**
     * Retrieve a record by the given ID.
     */
    public function find(int|string $id, ?array $columns = null): Builder|Model|bool|null
    {
        return $this->model::query()->find($id);
    }

    /**
     * Store a new record in the database.
     *
     * @param  array  $data  The data to be stored.
     * @return Model The created model instance.
     */
    public function store(array $data): Model
    {
        return $this->model::query()->create($data);
    }

    /**
     * Update a record by the given ID.
     */
    public function update(int|string $id, array $data): Builder|Model|bool|null
    {
        $item = $this->find($id);

        if (! $item) {
            return null;
        }

        return $item->update($data);
    }

    /**
     * Delete a record by the given ID.
     */
    public function delete(int|string $id): ?int
    {
        $item = $this->find($id);

        if (! $item) {
            return null;
        }

        return $this->model::destroy($id);
    }

    /**
     * Find records by criteria.
     */
    public function findBy(array $criteria, array $columns = [], bool $single = true): mixed
    {
        $query = $this->model::query();

        foreach ($criteria as $key => $value) {
            $query->where($key, $value);
        }
        $method = $single ? 'first' : 'get';

        if (! empty($columns)) {
            return $query->{$method}($columns);
        }

        return $query->{$method}();
    }

    /**
     * Update the records matched by the given criteria.
     *
     * @param  array  $criteria  The criteria to match.
     * @param  array  $data  The data to update.
     * @return int The number of affected rows.
     */
    public function updateBy(array $criteria, array $data): int
    {
        $query = $this->model::query();

        foreach ($criteria as $key => $value) {
            $query->where($key, $value);
        }

        return $query->update($data);
    }

    /**
     * Paginate the results of the model query.
     *
     * @param  int|null  $perPage  The number of results to display per page.
     * @param  array  $columns  The columns to select.
     * @param  string  $pageName  The name of the pagination parameter.
     * @param  int|null  $page  The page number to retrieve.
     */
    public function paginate($perPage = null, $columns = ['*'], $pageName = 'page', $page = null): LengthAwarePaginator
    {
        $query = $this->model::query();

        return $query->paginate($perPage, $columns, $pageName, $page);
    }
}

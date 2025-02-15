<?php

namespace App\Repositories\Eloquents;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;

class UserRepository extends BaseEloquentRepository implements UserRepositoryInterface
{
    protected $model = User::class;

    public function getUserBaseRole($roleRequest)
    {
        $query = $this->model::query();

        return $query->when($roleRequest, function ($q) use ($roleRequest) {

            $q->whereHas('roles', function ($q) use ($roleRequest) {
                $q->where('name', $roleRequest->name);
            });
        })
            ->orderBy('created_at', 'DESC')
            ->paginate();
    }

    public function allWithTrashed()
    {
        $query = $this->model::query();

        return $query->withTrashed()
            ->orderBy('created_at', 'DESC')
            ->paginate();
    }

    public function restoreUser(int|string $id)
    {
        $query = $this->model::query();

        return $query->withTrashed()->where('id', $id)->restore();
    }
}

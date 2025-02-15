<?php

namespace App\Repositories\Contracts;

interface UserRepositoryInterface extends BaseRepositoryInterface
{
    public function allWithTrashed();

    public function restoreUser(int|string $id);
}

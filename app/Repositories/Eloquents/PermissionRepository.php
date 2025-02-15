<?php

namespace App\Repositories\Eloquents;

use App\Models\Permission;
use App\Models\Role;
use App\Repositories\Contracts\PermissionRepositoryInterface;

class PermissionRepository extends BaseEloquentRepository implements PermissionRepositoryInterface
{
    protected $model = Permission::class;

    protected $roleModel = Role::class;

    public function setPermissionToRole(int $roleID, array $permissionIDs, bool $give = true): bool
    {
        $role = $this->roleModel::query()->find($roleID);

        if ($give) {
            return $role->givePermissionTo($permissionIDs);
        }

        return $role->revokePermissionTo($permissionIDs);
    }

    public function syncPermissionToRole(int $roleID, array $permissionIDs): bool
    {
        $role = $this->roleModel::query()->find($roleID);

        return $role->syncPermissions($permissionIDs);
    }

    public function syncPermissionsToRole(int $roleID, array $permissionIDs): bool
    {
        $role = $this->roleModel::query()->find($roleID);

        return $role->syncPermissions($permissionIDs);
    }
}

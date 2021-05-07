<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AssignRoleAndPermissionService
{
    /**
     * @var Role
     */
    protected $role;

    /**
     * @var Permission
     */
    protected $permission;

    /**
     * @var User
     */
    protected $user;

    /**
     * AssignRoleAndPermissionService constructor.
     * @param Role $role
     * @param Permission $permission
     * @param User $user
     */
    public function __construct(Role $role, Permission $permission, User $user)
    {
        $this->role = $role;
        $this->permission = $permission;
        $this->user = $user;
    }

    /**
     * Method to assign permissions to a role
     * @param $request
     */
    public function assignPermissionsToRole($request)
    {
        // fetch roles by id
        $role = $this->role->find($request->role_id);

        // fetch all the permissions
        $permissions = $this->permission->whereIn('name', $request->permissions)->get();

        // throw exception if unable to find the role
        if (!$role || $permissions->count() == 0)
            throw new ModelNotFoundException('Unable to find the role or permission');

        // attach permission to roles
        $role->syncPermissions($permissions);
    }

    /**
     * Method to assign roles to a permission
     * @param $request
     */
    public function assignRolesToPermission($request)
    {
        // fetch permission by permission id
        $permission = $this->permission->find($request->permission_id);

        // fetch all roles
        $roles = $this->role->whereIn('name', $request->roles)->get();

        if (!$permission || $roles->count() == 0)
            throw new ModelNotFoundException('Unable to find the role or permission');

        // attach roles to permission
        $permission->syncRoles($roles);
    }
}

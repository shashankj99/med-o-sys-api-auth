<?php

namespace App\Services;

use App\Http\Facades\AuthUser;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\UnauthorizedException;
use Spatie\Permission\Models\Role;

class RoleService
{
    /**
     * @var Role
     */
    protected $role;

    /**
     * RoleService constructor.
     * @param Role $role
     */
    public function __construct(Role $role)
    {
        $this->role = $role;
    }

    /**
     * Method to get all the roles
     * @param $request
     * @param int $limit
     * @return LengthAwarePaginator
     */
    public function getAllRoles($request, $limit = 10)
    {
        // throw unauthorized exception
        if (!AuthUser::hasRoles(['super admin']))
            throw new UnauthorizedException('Forbidden');

        $role = $this->role->query();

        // check if request has limit value
        if ($request->limit)
            $limit = $request->limit;

        // check if request has search term
        if ($request->search)
            $role->where('name', 'LIKE', "%{$request->search}%");

        return $role->paginate($limit);
    }

    /**
     * Method to create role
     * @param $request
     */
    public function createRole($request)
    {
        // throw unauthorized exception
        if (!AuthUser::hasRoles(['super admin']))
            throw new UnauthorizedException('Forbidden');

        $this->role->create(['name' => strtolower($request->name)]);
    }

    /**
     * Method to get role detail
     * @param $id
     * @return mixed
     */
    public function getRole($id)
    {
        // throw unauthorized exception
        if (!AuthUser::hasRoles(['super admin']))
            throw new UnauthorizedException('Forbidden');

        // fetch role by id
        $role = $this->role->find($id);

        // throw not found exception if unable to find the role
        if (!$role)
            throw new ModelNotFoundException('Unable to find the role');

        return $role;
    }

    /**
     * Method to update the role
     * @param $id
     * @param $request
     */
    public function updateRole($id, $request)
    {
        // throw unauthorized exception
        if (!AuthUser::hasRoles(['super admin']))
            throw new UnauthorizedException('Forbidden');

        // fetch the role by id
        $role = $this->role->find($id);

        // throw not found exception if unable to find the role
        if(!$role)
            throw New ModelNotFoundException('Unable to find the role');

        // update the role and save changes
        $role->name = $request->name;
        $role->save();
    }

    /**
     * Method to delete the role
     * @param $id
     */
    public function deleteRole($id)
    {
        // throw unauthorized exception
        if (!AuthUser::hasRoles(['super admin']))
            throw new UnauthorizedException('Forbidden');

        // fetch the role
        $role = $this->role->find($id);

        // throw not found exception if unable to find the role
        if (!$role)
            throw new ModelNotFoundException('Unable to find the role');

        // delete the role
        $role->delete();
    }
}

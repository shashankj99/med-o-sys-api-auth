<?php

namespace App\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
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
        $this->role->create(['name' => strtolower($request->name)]);
    }

    /**
     * Method to get role detail
     * @param $id
     * @return mixed
     */
    public function getRole($id)
    {
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
        // fetch the role
        $role = $this->role->find($id);

        // throw not found exception if unable to find the role
        if (!$role)
            throw new ModelNotFoundException('Unable to find the role');

        // delete the role
        $role->delete();
    }
}

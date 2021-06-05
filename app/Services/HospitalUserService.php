<?php

namespace App\Services;

use App\Http\Facades\AuthUser;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\UnauthorizedException;

class HospitalUserService
{
    /**
     * @var User
     */
    protected $user;

    /**
     * HospitalUserService constructor.
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Method to add hospital to the user
     * @param $request
     */
    public function add_hospital_to_user($request)
    {
        if (!AuthUser::hasRoles(['super admin']))
            throw new UnauthorizedException('Forbidden');

        // get the user
        $user = $this->user->find($request->user_id);

        // throw not found error
        if (!$user)
            throw new ModelNotFoundException('Unable to find the user');

        // get role/s of the user
        $userRoles = $user->getRoleNames()->toArray();

        // roles that must be present before adding hospital to the user
        $roles = ['super admin', 'hospital admin', 'doctor', 'nurse', 'para medic', 'accountant'];

        // check if the user has certain roles
        $_roles = array_filter($roles, function ($role) use ($userRoles) {
            return (in_array($role, $userRoles))
                ? true
                : false;
        });

        // throw unauthorized exception
        if (!$_roles)
            throw new UnauthorizedException('The user is missing a role to be a hospital member');

        // associate hospital to the user
        $user->hospital_user()
            ->create([
                'hospital_id' => $request->hospital_id
            ]);
    }

    /**
     * Method to get the hospital associated to the user
     * @param $id
     * @return mixed
     */
    public function show_hospital_associated_to_user($id)
    {
        return $this->get_hospital_associated_to_user($id);
    }

    /**
     * Method to update the hospital associated to the user
     * @param $request
     * @param $id
     */
    public function update_hospital_associated_to_user($request, $id)
    {
        $hospitalUser = $this->get_hospital_associated_to_user($id);
        $hospitalUser->hospital_id = $request->hospital_id;
        $hospitalUser->save();
    }

    /**
     * Method to delete the hospital associated to the user
     * @param $id
     */
    public function delete_hospital_associated_to_user($id)
    {
        $hospitalUser = $this->get_hospital_associated_to_user($id);
        $hospitalUser->delete();
    }

    /**
     * Method to get the hospital associated to the user
     * @param $id
     * @return mixed
     */
    private function get_hospital_associated_to_user($id)
    {
        if (!AuthUser::hasRoles(['super admin']))
            throw new UnauthorizedException('Forbidden');

        // get the user
        $user = $this->user->find($id);

        // throw not found error
        if (!$user)
            throw new ModelNotFoundException('Unable to find the user');

        // get the hospital user
        $hospitalUser = $user->hospital_user;

        // throw not found error
        if (!$hospitalUser)
            throw new ModelNotFoundException('No any hospital is associated to this user');

        return $hospitalUser;
    }
}

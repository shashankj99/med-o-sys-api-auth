<?php

namespace App\Services;

use App\Http\Facades\AuthUser;
use App\Models\User;
use App\Traits\GetUserAge;
use App\Traits\GetUserImage;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\UnauthorizedException;
use Illuminate\Validation\ValidationException;

class UserService
{
    use GetUserAge, GetUserImage;

    /**
     * @var User
     */
    protected $user;

    /**
     * UserService constructor.
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Get all users
     * @param $request
     * @param int $limit
     * @param string $role
     * @return LengthAwarePaginator
     */
    public function getAllUsers($request, $limit=10, $role='')
    {
        // throw unauthorized exception
        if (!AuthUser::hasRoles(['super admin']))
            throw new UnauthorizedException('Forbidden');

        // user query builder
        $users = $this->user->query();

        // date range filter
        if ($request->start_date && $request->end_date) {
            $startDate = Carbon::parse($request->start_date)->startOfDay()->toDayDateTimeString();
            $endDate = Carbon::parse($request->end_date)->endOfDay()->toDayDateTimeString();

            $users->whereBetween('crated_at', [$startDate, $endDate]);
        }

        // province filter
        if ($request->province)
            $users->where('province', $request->province);

        // district filter
        if ($request->district)
            $users->where('district', $request->district);

        // city filter
        if ($request->city)
            $users->where('city', $request->city);

        // blood group filter
        if ($request->blood_group)
            $users->where('blood_group', $request->blood_group);

        // status filter
        if ($request->status)
            $users->where('status', $request->status);

        // search filter
        if ($request->search)
            $users->whereLike(
                ['first_name', 'middle_name', 'last_name', 'nep_name', 'province', 'district', 'city', 'dob_ad',
                'dob_bs', 'mobile', 'email'], $request->search
            );

        // change role if request has one
        if ($request->role)
            $role = $request->role;

        // if request has limit
        if ($request->limit)
            $limit = $request->limit;

        return ($role == '')
            ? $users->paginate($limit)
            : $users->role($role)->paginate($limit);
    }

    /**
     * Method to get user by id
     * @param $id
     * @return mixed
     */
    public function getUserById($id)
    {
        // throw unauthorized exception
        if (!AuthUser::hasRoles(['super admin']))
            throw new UnauthorizedException('Forbidden');

        // fetch the user by id
        $user = $this->user->find($id);

        // throw not found exception
        if (!$user)
            throw new ModelNotFoundException('User with this id doesn\'t exist');

        return $user;
    }

    /**
     * Method to get user by access token
     * @return mixed
     */
    public function getUserByAccessToken()
    {
        return AuthUser::user();
    }

    /**
     * Method to update user by id
     * @param $id
     * @param $request
     * @throws ValidationException
     */
    public function updateUser($id, $request)
    {
        // throw unauthorized exception
        if (!AuthUser::hasRoles(['super admin']))
            throw new UnauthorizedException('Forbidden');

        // fetch the user by id
        $user = $this->user->find($id);

        // throw not found exception
        if (!$user)
            throw new ModelNotFoundException('User with this id doesn\'t exist');

        $this->update($user, $request);
    }

    /**
     * Method to update user profile
     * @param $request
     * @throws ValidationException
     */
    public function updateProfile($request)
    {
        // get user via access token
        $user = AuthUser::user();

        $this->update($user, $request);
    }

    /**
     * Method to delete the user
     * @param $id
     */
    public function deleteUser($id)
    {
        // throw unauthorized exception
        if (!AuthUser::hasRoles(['super admin']))
            throw new UnauthorizedException('Forbidden');

        // fetch the user by id
        $user = $this->user->find($id);

        // throw not found exception
        if (!$user)
            throw new ModelNotFoundException('User with this id doesn\'t exist');

        $user->delete();
    }

    /**
     * Method to update user
     * @param $user
     * @param $request
     * @throws ValidationException
     */
    private function update($user, $request)
    {
        // get user age
        $age = $this->getUserAge($request);

        // password changes
        $password = ($request->password)
            ? $request->password
            : $user->password;

        // image change
        if ($request->img && $request->img != $user->img) {
            // upload image to CDN service and get image name
            $image = $this->getImageName($request->img, $request->mobile);
        } else {
            // replace the CDN URL
            $image = str_replace(config('app.get_avatar_image_url'), "", $user->img);
        }

        // update user data
        $user->update([
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'nep_name' => $request->nep_name,
            'province' => $request->province,
            'district' => $request->district,
            'city' => $request->city,
            'ward_no' => $request->ward_no,
            'dob_ad' => $request->dob_ad,
            'dob_bs' => $request->dob_bs,
            'mobile' => $request->mobile,
            'email' => $request->email,
            'password' => $password,
            'age' => $age,
            'blood_group' => $request->blood_group,
            'img' => $image
        ]);
    }
}

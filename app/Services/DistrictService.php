<?php

namespace App\Services;

use App\Http\Facades\AuthUser;
use App\Models\District;
use App\Models\Province;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Str;
use Illuminate\Validation\UnauthorizedException;

class DistrictService
{
    /**
     * @var Province
     */
    protected $province;

    /**
     * DistrictService constructor.
     * @param Province $province
     */
    public function __construct(Province $province)
    {
        $this->province = $province;
    }

    /**
     * Method to get all the districts
     * @param $request
     * @param int $limit
     * @return LengthAwarePaginator
     */
    public function getAllDistricts($request, $limit=10)
    {
        // throw unauthorized exception
        if (!AuthUser::hasRoles(['super admin']))
            throw new UnauthorizedException('Forbidden');

        $districts = District::query();

        // check if request has limit
        if ($request->limit)
            $limit = $request->limit;

        // check if request has search term
        if ($request->search)
            $districts->where('name', 'LIKE', "%{$request->searcg}%");

        return $districts->paginate($limit);
    }

    /**
     * Method to create a new district
     * @param $provinceId
     * @param $request
     */
    public function createDistrict($provinceId, $request)
    {
        // throw unauthorized exception
        if (!AuthUser::hasRoles(['super admin']))
            throw new UnauthorizedException('Forbidden');

        // fetch province by id
        $province = $this->province->find($provinceId);

        // throw not found error
        if (!$province)
            throw new ModelNotFoundException('Unable to find the province');

        // create district
        $province->districts()->create([
            'name' => ucfirst($request->name),
            'slug' => Str::slug($request->name),
            'nep_name' => $request->nep_name
        ]);
    }

    /**
     * Method to find the district by province and district id
     * @param $provinceId
     * @param $districtId
     * @return mixed
     */
    public function getDistrict($provinceId, $districtId)
    {
        // throw unauthorized exception
        if (!AuthUser::hasRoles(['super admin']))
            throw new UnauthorizedException('Forbidden');

        // fetch province by id
        $province = $this->province->find($provinceId);

        // throw not found error
        if (!$province)
            throw new ModelNotFoundException('Unable to find the province');

        // get the district of the province
        $district = $province->districts()->find($districtId);

        // throw not found error
        if (!$district)
            throw new ModelNotFoundException('Unable to find the district');

        return $district;
    }

    /**
     * Method to update the district id
     * @param $provinceId
     * @param $districtId
     * @param $request
     */
    public function updateDistrict($provinceId, $districtId, $request)
    {
        // throw unauthorized exception
        if (!AuthUser::hasRoles(['super admin']))
            throw new UnauthorizedException('Forbidden');

        // fetch province by id
        $province = $this->province->find($provinceId);

        // throw not found error
        if (!$province)
            throw new ModelNotFoundException('Unable to find the province');

        // get the district of the province
        $district = $province->districts()->find($districtId);

        // throw not found error
        if (!$district)
            throw new ModelNotFoundException('Unable to find the district');

        // update the district changes
        $district->update([
            'name' => ucfirst($request->name),
            'slug' => Str::slug($request->name),
            'nep_name' => $request->nep_name
        ]);
    }

    /**
     * Method to delete the district
     * @param $provinceId
     * @param $districtId
     */
    public function deleteDistrict($provinceId, $districtId)
    {
        // throw unauthorized exception
        if (!AuthUser::hasRoles(['super admin']))
            throw new UnauthorizedException('Forbidden');

        // fetch province by id
        $province = $this->province->find($provinceId);

        // throw not found error
        if (!$province)
            throw new ModelNotFoundException('Unable to find the province');

        // get the district of the province
        $district = $province->districts()->find($districtId);

        // throw not found error
        if (!$district)
            throw new ModelNotFoundException('Unable to find the district');

        $district->delete();
    }
}

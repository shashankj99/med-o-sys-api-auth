<?php

namespace App\Services;

use App\Http\Facades\AuthUser;
use App\Models\Province;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Str;
use Illuminate\Validation\UnauthorizedException;

class ProvinceService
{
    /**
     * @var Province
     */
    protected $province;

    /**
     * ProvinceService constructor.
     * @param Province $province
     */
    public function __construct(Province $province)
    {
        $this->province = $province;
    }

    /**
     * Method to get all the provinces
     * @param $request
     * @return Builder[]|Collection
     */
    public function getAllProvinces($request)
    {
        $provinces = $this->province->query();

        // check if request has search
        if ($request->search)
            $provinces->where('name', 'LIKE', "%{$request->search}%");

        return $provinces->get();
    }

    /**
     * Method to create a new province
     * @param $request
     */
    public function createProvince($request)
    {
        // throw unauthorized exception
        if (!AuthUser::hasRoles(['super admin']))
            throw new UnauthorizedException('Forbidden');

        $this->province->create([
            'name' => ucfirst($request->name),
            'slug' => Str::slug($request->name),
            'nep_name' => $request->nep_name
        ]);
    }

    /**
     * Method to find the province by id
     * @param $id
     * @return mixed
     */
    public function getProvince($id)
    {
        // throw unauthorized exception
        if (!AuthUser::hasRoles(['super admin']))
            throw new UnauthorizedException('Forbidden');

        // fetch the province by id
        $province = $this->province->find($id);

        // throw not found exception if unable to find the province
        if (!$province)
            throw new ModelNotFoundException('Unable to find the province');

        return $province;
    }

    /**
     * Method to update the province data
     * @param $id
     * @param $request
     */
    public function updateProvince($id, $request)
    {
        // throw unauthorized exception
        if (!AuthUser::hasRoles(['super admin']))
            throw new UnauthorizedException('Forbidden');

        // fetch province by id
        $province = $this->province->find($id);

        // throw not found exception if unable to find the province
        if (!$province)
            throw new ModelNotFoundException('Unable to find the province');

        // update the changes
        $province->update([
            'name' => ucfirst($request->name),
            'slug' => Str::slug($request->name),
            'nep_name' => $request->nep_name
        ]);
    }

    /**
     * Method to delete the province data
     * @param $id
     */
    public function deleteProvince($id)
    {
        // throw unauthorized exception
        if (!AuthUser::hasRoles(['super admin']))
            throw new UnauthorizedException('Forbidden');

        // fetch the province by id
        $province = $this->province->find($id);

        // throw not found exception if unable to find the province
        if (!$province)
            throw new ModelNotFoundException('Unable to find the province');

        $province->delete();
    }

    /**
     * @description Method to get location info
     * @param $request
     * @return array
     */
    public function getLocationInfo($request)
    {
        // get province, district and city name
        $location = $this->province
            ->select('id', 'name', 'nep_name')
            ->with('districts.cities')
            ->whereHas('districts', function ($query) use ($request) {
                $query
                    ->select(['id', 'province_id', 'name', 'nep_name'])
                    ->whereHas('cities', function ($q) use ($request) {
                        $q
                        ->select(['id', 'district_id', 'name', 'nep_name'])
                        ->where('id', '=', $request->city_id);
                    })
                    ->where('id', '=', $request->district_id);
            })
            ->find($request->province_id);
        
        // throw not found exception 
        if (!$location)
            throw new ModelNotFoundException("Unable to find the location information");
        
        // district and city model
        $district = $location->districts[0];
        $city = $location->districts[0]->cities[0];

        return [
            'province' => "$location->name, ($location->nep_name)",
            'district' => "$district->name, ($district->nep_name)",
            'city'     => "$city->name, ($city->nep_name)"
        ];
    }
}

<?php

namespace App\Services;

use App\Http\Facades\AuthUser;
use App\Models\City;
use App\Models\District;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Str;
use Illuminate\Validation\UnauthorizedException;

class CityService
{
    /**
     * @var District
     */
    protected $district;

    /**
     * CityService constructor.
     * @param District $district
     */
    public function __construct(District $district)
    {
        $this->district = $district;
    }

    /**
     * Method to get all the cities
     * @param $request
     * @param int $limit
     * @return LengthAwarePaginator
     */
    public function getAllCities($request, $limit=10)
    {
        $city = City::query();

        // check if request has limit
        if ($request->limit)
            $limit = $request->limit;

        // check if request has district id
        if ($request->district_id)
            $city->where('district_id', $request->district_id);

        // check if request has search term
        if ($request->search)
            $city->where('name', 'LIKE', "%{$request->search}%");

        return $city->paginate($limit);
    }

    /**
     * Method to create a city
     * @param $districtId
     * @param $request
     */
    public function createCity($districtId, $request)
    {
        // throw unauthorized exception
        if (!AuthUser::hasRoles(['super admin']))
            throw new UnauthorizedException('Forbidden');

        // fetch the district by id
        $district = $this->district->find($districtId);

        // throw not found error
        if (!$district)
            throw new ModelNotFoundException('Unable to find the district');

        $district->cities()->create([
            'name'          => ucfirst($request->name),
            'slug'          => Str::slug($request->name),
            'nep_name'      => $request->nep_name,
            'total_ward_no' => $request->total_ward_no
        ]);
    }

    /**
     * Method to get detail of a city
     * @param $districtId
     * @param $cityId
     * @return mixed
     */
    public function getCity($districtId, $cityId)
    {
        // throw unauthorized exception
        if (!AuthUser::hasRoles(['super admin']))
            throw new UnauthorizedException('Forbidden');

        // fetch the district by id
        $district = $this->district->find($districtId);

        // throw not found error
        if (!$district)
            throw new ModelNotFoundException('Unable to find the district');

        // fetch particular city of the district
        $city = $district->cities()->find($cityId);

        // throw not found error
        if (!$city)
            throw new ModelNotFoundException('Unable to find the city');

        return $city;
    }

    /**
     * Method to update the city data
     * @param $districtId
     * @param $cityId
     * @param $request
     */
    public function updateCity($districtId, $cityId, $request)
    {
        // throw unauthorized exception
        if (!AuthUser::hasRoles(['super admin']))
            throw new UnauthorizedException('Forbidden');

        // fetch the district by id
        $district = $this->district->find($districtId);

        // throw not found error
        if (!$district)
            throw new ModelNotFoundException('Unable to find the district');

        // fetch particular city of the district
        $city = $district->cities()->find($cityId);

        // throw not found error
        if (!$city)
            throw new ModelNotFoundException('Unable to find the city');

        $city->update([
            'name'          => ucfirst($request->name),
            'slug'          => Str::slug($request->name),
            'nep_name'      => $request->nep_name,
            'total_ward_no' => $request->total_ward_no
        ]);
    }

    /**
     * Method to delete the city
     * @param $districtId
     * @param $cityId
     */
    public function deleteCity($districtId, $cityId)
    {
        // throw unauthorized exception
        if (!AuthUser::hasRoles(['super admin']))
            throw new UnauthorizedException('Forbidden');

        // fetch the district by id
        $district = $this->district->find($districtId);

        // throw not found error
        if (!$district)
            throw new ModelNotFoundException('Unable to find the district');

        // fetch particular city of the district
        $city = $district->cities()->find($cityId);

        // throw not found error
        if (!$city)
            throw new ModelNotFoundException('Unable to find the city');

        $city->delete();
    }
}

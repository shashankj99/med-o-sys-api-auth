<?php

namespace App\Services;

use App\Models\City;
use App\Models\District;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Str;

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
        // fetch the district by id
        $district = $this->district->find($districtId);

        // throw not found error
        if (!$district)
            throw new ModelNotFoundException('Unable to find the district');

        $district->cities()->create([
            'name' => ucfirst($request->name),
            'slug' => Str::slug($request->name),
            'nep_name' => $request->nep_name
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
            'name' => ucfirst($request->name),
            'slug' => Str::slug($request->name),
            'nep_name' => $request->nep_name
        ]);
    }

    /**
     * Method to delete the city
     * @param $districtId
     * @param $cityId
     */
    public function deleteCity($districtId, $cityId)
    {
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

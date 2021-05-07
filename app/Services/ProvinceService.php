<?php

namespace App\Services;

use App\Models\Province;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Str;

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
        // fetch the province by id
        $province = $this->province->find($id);

        // throw not found exception if unable to find the province
        if (!$province)
            throw new ModelNotFoundException('Unable to find the province');

        $province->delete();
    }
}
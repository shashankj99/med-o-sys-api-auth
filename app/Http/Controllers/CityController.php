<?php

namespace App\Http\Controllers;

use App\Services\CityService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\UnauthorizedException;
use Illuminate\Validation\ValidationException;

class CityController extends Controller
{
    /**
     * @var CityService
     */
    protected $cityService;

    /**
     * CityController constructor.
     * @param CityService $cityService
     */
    public function __construct(CityService $cityService)
    {
        $this->cityService = $cityService;
    }

    /**
     * @param Request $request
     * @return LengthAwarePaginator|JsonResponse
     */
    public function index(Request $request)
    {
        try {
            return $this->cityService->getAllCities($request);
        } catch (\Exception $exception) {
            return response()->json([
                'status' => 500,
                'message' => $exception->getMessage()
            ], 500);
        }
    }

    /**
     * Method to create city
     * @param $districtId
     * @param Request $request
     * @return JsonResponse
     */
    public function store($districtId, Request $request)
    {
        try {
            $this->validate($request, [
                'name'          => 'required|unique:cities',
                'nep_name'      => 'required|unique:cities',
                'total_ward_no' => 'required'
            ], [
                '*.required' => 'This field is required',
                '*.unique' => 'This value has already been taken'
            ]);

            $this->cityService->createCity($districtId, $request);

            return response()->json([
                'status' => 200,
                'message' => 'City created successfully'
            ], 200);
        } catch (ValidationException $exception) {
            return response()->json([
                'status' => 422,
                'errors' => $exception->errors()
            ], 422);
        } catch (UnauthorizedException $exception) {
            return response()->json([
                'status'    => 401,
                'message'   => $exception->getMessage()
            ], 401);
        } catch (ModelNotFoundException $exception) {
            return response()->json([
                'status' => 404,
                'message' => $exception->getMessage()
            ], 404);
        } catch (\Exception $exception) {
            return response()->json([
                'status' => 500,
                'message' => $exception->getMessage()
            ], 500);
        }
    }

    /**
     * Method to get detail of a city
     * @param $districtId
     * @param $id
     * @return JsonResponse
     */
    public function show($districtId, $id)
    {
        try {
            $city = $this->cityService->getCity($districtId, $id);

            return response()->json([
                'status' => 200,
                'data' => $city
            ], 200);
        } catch (UnauthorizedException $exception) {
            return response()->json([
                'status'    => 401,
                'message'   => $exception->getMessage()
            ], 401);
        } catch (ModelNotFoundException $exception) {
            return response()->json([
                'status' => 404,
                'message' => $exception->getMessage()
            ], 404);
        } catch (\Exception $exception) {
            return response()->json([
                'status' => 500,
                'message' => $exception->getMessage()
            ], 500);
        }
    }

    /**
     * Method to update the city data
     * @param $districtId
     * @param $id
     * @param Request $request
     * @return JsonResponse
     */
    public function update($districtId, $id, Request $request)
    {
        try {
            $this->validate($request, [
                'name'          => 'required|unique:cities,name,' . $id,
                'nep_name'      => 'required|unique:cities,nep_name,' . $id,
                'total_ward_no' => 'required'
            ], [
                '*.required' => 'This field is required',
                '*.unique' => 'This value has already been taken'
            ]);

            $this->cityService->updateCity($districtId, $id, $request);

            return response()->json([
                'status' => 200,
                'message' => 'City updated successfully'
            ], 200);
        } catch (ValidationException $exception) {
            return response()->json([
                'status' => 422,
                'errors' => $exception->errors()
            ], 422);
        } catch (UnauthorizedException $exception) {
            return response()->json([
                'status'    => 401,
                'message'   => $exception->getMessage()
            ], 401);
        } catch (ModelNotFoundException $exception) {
            return response()->json([
                'status' => 404,
                'message' => $exception->getMessage()
            ], 404);
        } catch (\Exception $exception) {
            return response()->json([
                'status' => 500,
                'message' => $exception->getMessage()
            ], 500);
        }
    }

    /**
     * Method to delete the city
     * @param $districtId
     * @param $id
     * @return JsonResponse
     */
    public function destroy($districtId, $id)
    {
        try {
            $this->cityService->deleteCity($districtId, $id);

            return response()->json([
                'status' => 200,
                'message' => 'City deleted successfully'
            ], 200);
        } catch (UnauthorizedException $exception) {
            return response()->json([
                'status'    => 401,
                'message'   => $exception->getMessage()
            ], 401);
        } catch (ModelNotFoundException $exception) {
            return response()->json([
                'status' => 404,
                'message' => $exception->getMessage()
            ], 404);
        } catch (\Exception $exception) {
            return response()->json([
                'status' => 500,
                'message' => $exception->getMessage()
            ], 500);
        }
    }
}

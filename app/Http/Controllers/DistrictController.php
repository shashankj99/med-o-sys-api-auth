<?php

namespace App\Http\Controllers;

use App\Services\DistrictService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\UnauthorizedException;
use Illuminate\Validation\ValidationException;

class DistrictController extends Controller
{
    /**
     * @var DistrictService
     */
    protected $districtService;

    /**
     * DistrictController constructor.
     * @param DistrictService $districtService
     */
    public function __construct(DistrictService $districtService)
    {
        $this->districtService = $districtService;
    }

    /**
     * Method to get all the districts
     * @param Request $request
     * @return LengthAwarePaginator|JsonResponse
     */
    public function index(Request $request)
    {
        try {
            return $this->districtService->getAllDistricts($request);
        } catch (UnauthorizedException $exception) {
            return response()->json([
                'status'    => 401,
                'message'   => $exception->getMessage()
            ], 401);
        } catch (\Exception $exception) {
            return response()->json([
                'status' => 500,
                'message' => $exception->getMessage()
            ], 500);
        }
    }

    /**
     * Method to create the district
     * @param $provinceId
     * @param Request $request
     * @return JsonResponse
     */
    public function store($provinceId, Request $request)
    {
        try {
            $this->validate($request, [
                'name' => 'required|unique:districts',
                'nep_name' => 'required|unique:districts'
            ], [
                '*.required' => 'This field is required',
                '*.unique' => 'This value has already been taken'
            ]);

            $this->districtService->createDistrict($provinceId, $request);

            return response()->json([
                'status' => 200,
                'message' => 'District created successfully'
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
     * Method to get district by province id and district id
     * @param $provinceId
     * @param $id
     * @return JsonResponse
     */
    public function show($provinceId, $id)
    {
        try {
            $district = $this->districtService->getDistrict($provinceId, $id);

            return response()->json([
                'status' => 200,
                'data' => $district
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
     * Method to update the district data
     * @param $provinceId
     * @param $id
     * @param Request $request
     * @return JsonResponse
     */
    public function update($provinceId, $id, Request $request)
    {
        try {
            $this->validate($request, [
                'name' => 'required|unique:districts,name,' . $id,
                'nep_name' => 'required|unique:districts,nep_name,' . $id
            ], [
                '*.required' => 'This field is required',
                '*.unique' => 'This value has already been taken'
            ]);

            $this->districtService->updateDistrict($provinceId, $id, $request);

            return response()->json([
                'status' => 200,
                'message' => 'District updated successfully'
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
     * Method to delete the district
     * @param $provinceId
     * @param $id
     * @return JsonResponse
     */
    public function destroy($provinceId, $id)
    {
        try {
            $this->districtService->deleteDistrict($provinceId, $id);

            return response()->json([
                'status' => 200,
                'message' => 'District deleted successfully'
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

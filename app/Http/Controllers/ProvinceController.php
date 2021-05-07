<?php

namespace App\Http\Controllers;

use App\Services\ProvinceService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ProvinceController extends Controller
{
    /**
     * @var ProvinceService
     */
    protected $provinceService;

    /**w
     * ProvinceController constructor.
     * @param ProvinceService $provinceService
     */
    public function __construct(ProvinceService $provinceService)
    {
        $this->provinceService = $provinceService;
    }

    /**
     * @param Request $request
     * @return Builder[]|Collection|JsonResponse
     */
    public function index(Request $request)
    {
        try {
            return $this->provinceService->getAllProvinces($request);
        } catch (\Exception $exception) {
            return response()->json([
                'status' => 500,
                'message' => $exception->getMessage()
            ], 500);
        }
    }

    /**
     * Method to create a new province
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        try {
            $this->validate($request, [
                'name' => 'required|unique:provinces',
                'nep_name' => 'required|unique:provinces'
            ], [
                '*.required' => 'This field is required',
                '*.unique' => 'This value has already been taken'
            ]);

            $this->provinceService->createProvince($request);

            return response()->json([
                'status' => 200,
                'message' => 'Province created successfully'
            ], 200);
        } catch (ValidationException $exception) {
            return response()->json([
                'status' => 422,
                'errors' => $exception->errors()
            ], 422);
        } catch (\Exception $exception) {
            return response()->json([
                'status' => 500,
                'message' => $exception->getMessage()
            ], 500);
        }
    }

    /**
     * Method to show the province by id
     * @param $id
     * @return JsonResponse
     */
    public function show($id)
    {
        try {
            $province = $this->provinceService->getProvince($id);

            return response()->json([
                'status' => 200,
                'data' => $province
            ], 200);
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
     * Method to update the province data
     * @param $id
     * @param Request $request
     * @return JsonResponse
     */
    public function update($id, Request $request)
    {
        try {
            $this->validate($request, [
                'name' => 'required|unique:provinces,name,' . $id,
                'nep_name' => 'required|unique:provinces,name,' . $id
            ], [
                '*.required' => 'This field is required',
                '*.unique' => 'This value has already been taken'
            ]);

            $this->provinceService->updateProvince($id, $request);

            return response()->json([
                'status' => 200,
                'message' => 'Province updated successfully'
            ], 200);
        } catch (ValidationException $exception) {
            return response()->json([
                'status' => 422,
                'errors' => $exception->errors()
            ], 422);
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
     * method to delete the province data
     * @param $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        try {
            $this->provinceService->deleteProvince($id);

            return response()->json([
                'status' => 200,
                'message' => 'Province deleted successfully'
            ], 200);
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

<?php

namespace App\Http\Controllers;

use App\Services\PermissionService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class PermissionController extends Controller
{
    /**
     * @var PermissionService
     */
    protected $permissionService;

    /**
     * PermissionController constructor.
     * @param PermissionService $permissionService
     */
    public function __construct(PermissionService $permissionService)
    {
        $this->permissionService = $permissionService;
    }

    /**
     * Method to get all the permissions
     * @param Request $request
     * @return LengthAwarePaginator|JsonResponse
     */
    public function index(Request $request)
    {
        try {
            return $this->permissionService->getAllPermissions($request);
        } catch (\Exception $exception) {
            return response()->json([
                'status' => 500,
                'message' => $exception->getMessage()
            ], 500);
        }
    }

    /**
     * Method to create a new permission
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        try {
            $this->validate($request, [
                'name' => 'required|string|unique:permissions'
            ], [
                'name.required' => 'The name field is required',
                'name.string' => 'The name must be a string',
                'name.unique' => 'This name has already been taken'
            ]);

            $this->permissionService->createPermission($request);

            return response()->json([
                'status' => 200,
                'message' => 'Permission created successfully'
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
     * Method to find the permission detail
     * @param $id
     * @return JsonResponse
     */
    public function show($id)
    {
        try {
            $permission = $this->permissionService->getPermission($id);

            return response()->json([
                'status' => 200,
                'data' => $permission
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
     * Method to update the permission
     * @param $id
     * @param Request $request
     * @return JsonResponse
     */
    public function update($id, Request $request)
    {
        try {
            $this->validate($request, [
                'name' => 'required|string|unique:permissions,name,' . $id
            ], [
                'name.required' => 'The name field is required',
                'name.string' => 'The name must be a string',
                'name.unique' => 'This name has already been taken'
            ]);

            $this->permissionService->updatePermission($id, $request);

            return response()->json([
                'status' => 200,
                'message' => 'Permission updated successfully'
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
     * Method to delete the permission
     * @param $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        try {
            $this->permissionService->deletePermission($id);

            return response()->json([
                'status' => 200,
                'message' => 'Permission deleted successfully'
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

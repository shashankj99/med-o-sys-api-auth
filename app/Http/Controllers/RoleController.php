<?php

namespace App\Http\Controllers;

use App\Services\RoleService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class RoleController extends Controller
{
    /**
     * @var RoleService
     */
    protected $roleService;

    /**
     * RoleController constructor.
     * @param RoleService $roleService
     */
    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
    }

    /**
     * Method to get all the roles
     * @param Request $request
     * @return LengthAwarePaginator|JsonResponse
     */
    public function index(Request $request)
    {
        try {
            return $this->roleService->getAllRoles($request);
        } catch (\Exception $exception) {
            return response()->json([
                'status' => 500,
                'message' => $exception->getMessage()
            ], 500);
        }
    }

    /**
     * Method to create a role
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        try {
            $this->validate($request, [
                'name' => 'required|string|unique:roles'
            ], [
                'name.required' => 'The name field is required',
                'name.string' => 'The name must be a string',
                'name.unique' => 'This name has already been taken'
            ]);

            $this->roleService->createRole($request);

            return response()->json([
                'status' => 200,
                'message' => 'Role created successfully'
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
     * Method to get the details of a role
     * @param $id
     * @return JsonResponse
     */
    public function show($id)
    {
        try {
            $role = $this->roleService->getRole($id);

            return response()->json([
                'status' => 200,
                'data' => $role
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
     * Method to update the role
     * @param $id
     * @param Request $request
     * @return JsonResponse
     */
    public function update($id, Request $request)
    {
        try {
            $this->validate($request, [
                'name' => 'required|string|unique:roles,name,' . $id
            ], [
                'name.required' => 'The name field is required',
                'name.string' => 'The name must be a string',
                'name.unique' => 'This name has already been taken'
            ]);

            $this->roleService->updateRole($id, $request);

            return response()->json([
                'status' => 200,
                'message' => 'Role updated successfully'
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
     * Method to delete the role
     * @param $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        try {
            $this->roleService->deleteRole($id);

            return response()->json([
                'status' => 200,
                'message' => 'Role deleted successfully'
            ], 200);
        } catch (ModelNotFoundException $exception) {
            return response()->json([
                'status' => 404,
                'message' => $exception->getMessage()
            ], 404);
        }
    }
}

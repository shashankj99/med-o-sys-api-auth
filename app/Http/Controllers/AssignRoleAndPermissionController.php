<?php

namespace App\Http\Controllers;

use App\Services\AssignRoleAndPermissionService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AssignRoleAndPermissionController extends Controller
{
    /**
     * @var AssignRoleAndPermissionService
     */
    protected $assignRoleAndPermissionService;

    /**
     * AssignRoleAndPermissionController constructor.
     * @param AssignRoleAndPermissionService $assignRoleAndPermissionService
     */
    public function __construct(AssignRoleAndPermissionService $assignRoleAndPermissionService)
    {
        $this->assignRoleAndPermissionService = $assignRoleAndPermissionService;
    }

    /**
     * Method that returns validation message
     * @return array
     */
    private function validationMessages()
    {
        return [
            '*.required' => 'This field is required',
            '*.min' => 'This field must have at least a minimum value of 1',
            '*.array' => 'This field must be an array'
        ];
    }

    /**
     * Method that assigns permissions to a role
     * @param Request $request
     * @return JsonResponse
     */
    public function assignPermissionsToRole(Request $request)
    {
        try {
            $this->validate($request, [
                'role_id' => 'required|min:1',
                'permissions' => 'required|array'
            ], $this->validationMessages());

            $this->assignRoleAndPermissionService->assignPermissionsToRole($request);

            return response()->json([
                'status' => 200,
                'message' => 'Permissions were successfully assigned to the role'
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
     * Method to assign roles to a permission
     * @param Request $request
     * @return JsonResponse
     */
    public function assignRolesToPermission(Request $request)
    {
        try {
            $this->validate($request, [
                'permission_id' => 'required|min:1',
                'roles' => 'required|array'
            ], $this->validationMessages());

            $this->assignRoleAndPermissionService->assignRolesToPermission($request);

            return response()->json([
                'status' => 200,
                'message' => 'Roles were successfully assigned to the permission'
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
}

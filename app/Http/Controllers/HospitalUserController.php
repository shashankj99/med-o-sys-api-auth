<?php

namespace App\Http\Controllers;

use App\Services\HospitalUserService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\UnauthorizedException;
use Illuminate\Validation\ValidationException;

class HospitalUserController extends Controller
{
    /**
     * @var HospitalUserService
     */
    protected $hospitalUserService;

    /**
     * HospitalUserController constructor.
     * @param HospitalUserService $hospitalUserService
     */
    public function __construct(HospitalUserService $hospitalUserService)
    {
        $this->hospitalUserService = $hospitalUserService;
    }

    /**
     * Method to add hospital to the user
     * @param Request $request
     * @return JsonResponse
     */
    public function add_hospital_to_user(Request $request)
    {
        try {
            $this->validate($request, [
                'user_id'       => 'required|numeric|min:1',
                'hospital_id'   => 'required|numeric|min:1'
            ], [
                '*.required'    => 'This field is required',
                '*.numeric'     => 'This field must be a number',
                '*.min'         => 'The minimum value for this field must be 1'
            ]);

            $this->hospitalUserService->add_hospital_to_user($request);

            return response()->json([
                'status'    => 200,
                'message'   => 'The user is successfully added to the hospital'
            ], 200);

        } catch (ValidationException $exception) {
            return response()->json([
                'status'    => 422,
                'errors'    => $exception->errors()
            ], 422);
        } catch (UnauthorizedException $exception) {
            return response()->json([
                'status'    => 401,
                'message'   => $exception->getMessage(),
            ], 401);
        } catch (ModelNotFoundException $exception) {
            return response()->json([
                'status'    => 404,
                'message'   => $exception->getMessage()
            ], 404);
        } catch (\Exception $exception) {
            return response()->json([
                'status'    => 500,
                'message'   => $exception->getMessage()
            ], 500);
        }
    }

    /**
     * Method to get the hospital associated to the user
     * @param $id
     * @return JsonResponse
     */
    public function show_hospital_associated_to_user($id)
    {
        try {
            $hospitalAssociatedToUser = $this->hospitalUserService->show_hospital_associated_to_user($id);

            return response()->json([
                'status' => 200,
                'data'   => $hospitalAssociatedToUser
            ], 200);
        } catch (UnauthorizedException $exception) {
            return response()->json([
                'status'    => 401,
                'message'   => $exception->getMessage(),
            ], 401);
        } catch (ModelNotFoundException $exception) {
            return response()->json([
                'status'    => 404,
                'message'   => $exception->getMessage()
            ], 404);
        } catch (\Exception $exception) {
            return response()->json([
                'status'    => 500,
                'message'   => $exception->getMessage()
            ], 500);
        }
    }

    /**
     * Method to update the hospital associate to the user
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function update_hospital_associated_to_user(Request $request, $id)
    {
        try {
            $this->validate($request, [
                'hospital_id'   => 'required|numeric|min:1'
            ], [
                'hospital_id.required'    => 'The hospital id field is required',
                'hospital_id.numeric'     => 'The hospital id field must be a number',
                'hospital_id.min'         => 'The minimum value for The hospital id field must be 1'
            ]);

            $this->hospitalUserService->update_hospital_associated_to_user($request, $id);

            return response()->json([
                'status'    => 200,
                'message'   => 'The hospital associated to the user has been updated'
            ], 200);
        } catch (ValidationException $exception) {
            return response()->json([
                'status'    => 422,
                'errors'    => $exception->errors()
            ], 422);
        } catch (UnauthorizedException $exception) {
            return response()->json([
                'status'    => 401,
                'message'   => $exception->getMessage(),
            ], 401);
        } catch (ModelNotFoundException $exception) {
            return response()->json([
                'status'    => 404,
                'message'   => $exception->getMessage()
            ], 404);
        } catch (\Exception $exception) {
            return response()->json([
                'status'    => 500,
                'message'   => $exception->getMessage()
            ], 500);
        }
    }

    /**
     * Method to delete the hospital associated to the user
     * @param $id
     * @return JsonResponse
     */
    public function delete_hospital_associated_to_user($id)
    {
        try {
            $this->hospitalUserService->delete_hospital_associated_to_user($id);

            return response()->json([
                'status'    => 200,
                'message'   => 'The hospital associated to the user is deleted'
            ], 200);
        } catch (UnauthorizedException $exception) {
            return response()->json([
                'status'    => 401,
                'message'   => $exception->getMessage(),
            ], 401);
        } catch (ModelNotFoundException $exception) {
            return response()->json([
                'status'    => 404,
                'message'   => $exception->getMessage()
            ], 404);
        } catch (\Exception $exception) {
            return response()->json([
                'status'    => 500,
                'message'   => $exception->getMessage()
            ], 500);
        }
    }
}

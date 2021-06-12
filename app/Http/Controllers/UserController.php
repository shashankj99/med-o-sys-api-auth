<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Services\UserService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\UnauthorizedException;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    /**
     * @var UserService
     */
    protected $userService;

    /**
     * UserController constructor.
     * @param UserService $userService
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Method to get all the users
     * @param Request $request
     * @param UserRequest $userRequest
     * @return JsonResponse
     */
    public function index(Request $request, UserRequest $userRequest)
    {
        try {
            $userRequest->validateRequest($request->all());

            $users = $this->userService->getAllUsers($request);

            return response()->json([
                'status'    => 200,
                'data'      => $users
            ], 200);
        } catch (ValidationException $exception) {
            return response()->json([
                'status'    => 422,
                'errors'  => $exception->errors()
            ], 422);
        } catch (UnauthorizedException $exception) {
            return response()->json([
                'status'    => 401,
                'message'   => $exception->getMessage()
            ], 401);
        } catch (\Exception $exception) {
            return response()->json([
                'status'    => 500,
                'message'   => $exception->getMessage()
            ], 500);
        }
    }

    /**
     * Method to get the user by id
     * @param $id
     * @return JsonResponse
     */
    public function show($id)
    {
        try {
            $user = $this->userService->getUserById($id);

            return response()->json([
                'status'    => 200,
                'data'      => $user
            ], 200);
        } catch (UnauthorizedException $exception) {
            return response()->json([
                'status'    => 401,
                'message'   => $exception->getMessage()
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
     * Method to get user by access token
     * @return JsonResponse
     */
    public function getUserByAccessToken()
    {
        try {
            $user = $this->userService->getUserByAccessToken();

            return response()->json([
                'status'    => 200,
                'data'      => $user
            ], 200);
        } catch (\Exception $exception) {
            return response()->json([
                'status'    => 500,
                'message'   => $exception->getMessage()
            ], 500);
        }
    }

    /**
     * Update user by id
     * @param $id
     * @param Request $request
     * @param UserUpdateRequest $userUpdateRequest
     * @return JsonResponse
     */
    public function updateUser($id, Request $request, UserUpdateRequest $userUpdateRequest)
    {
        try {
            $userUpdateRequest->validateRequest($request->all());

            $this->userService->updateUser($id, $request);

            return response()->json([
                'status'    => 200,
                'message'   => 'User has been updated successfully'
            ], 200);
        } catch (ValidationException $exception) {
            return response()->json([
                'status'    => 422,
                'message'   => $exception->errors()
            ], 422);
        } catch (UnauthorizedException $exception) {
            return response()->json([
                'status'    => 401,
                'message'   => $exception->getMessage()
            ], 401);
        } catch (ModelNotFoundException $exception) {
            return response()->json([
                'status'    => 404,
                'message'   => $exception->getMessage()
            ], 404);
        } catch (RequestException $exception) {
            return response()->json([
                'status' => $exception->getCode(),
                'message' => $exception->getMessage()
            ], $exception->getCode());
        } catch (\Exception $exception) {
            return response()->json([
                'status'    => 500,
                'message'   => $exception->getMessage()
            ], 500);
        }
    }

    /**
     * Method to update user profile
     * @param Request $request
     * @param UserUpdateRequest $userUpdateRequest
     * @return JsonResponse
     */
    public function updateProfile(Request $request, UserUpdateRequest $userUpdateRequest)
    {
        try {
            $userUpdateRequest->validateRequest($request->all());

            $this->userService->updateProfile($request);

            return response()->json([
                'status' => 200,
                'message' => "Profile Updated successfully"
            ], 200);
        } catch (ValidationException $exception) {
            return response()->json([
                'status'    => 422,
                'message'   => $exception->errors()
            ], 422);
        } catch (RequestException $exception) {
            return response()->json([
                'status' => $exception->getCode(),
                'message' => $exception->getMessage()
            ], $exception->getCode());
        } catch (\Exception $exception) {
            return response()->json([
                'status'    => 500,
                'message'   => $exception->getMessage()
            ], 500);
        }
    }

    /**
     * Method to delete the user
     * @param $id
     * @return JsonResponse
     */
    public function deleteUser($id)
    {
        try {
            $this->userService->deleteUser($id);

            return response()->json([
                'status' => 200,
                'message' => 'User deleted successfully'
            ], 200);
        } catch (UnauthorizedException $exception) {
            return response()->json([
                'status'    => 401,
                'message'   => $exception->getMessage()
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
     * Method to get the user as json serialized
     * @param Request $request
     * @return JsonResponse
     */
    public function get_serialized_user(Request $request)
    {
        try {
            $this->validate($request, [
                'email' => 'required|email'
            ], [
                'email.required' => 'The email address of a user is required',
                'email.email' => 'The email address must be a valid email'
            ]);

            $user = $this->userService->get_serialized_user($request);

            return response()->json([
                'status'    => 200,
                'data'      => $user
            ], 200);
        } catch (ValidationException $exception) {
            return response()->json([
                'status'    => 422,
                'message'   => $exception->errors()
            ], 422);
        } catch (UnauthorizedException $exception) {
            return response()->json([
                'status'    => 401,
                'message'   => $exception->getMessage()
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

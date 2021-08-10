<?php

namespace App\Http\Controllers;

use App\Exceptions\CustomException;
use App\Http\Requests\RegisterRequest;
use App\Services\AuthService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\UnauthorizedException;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * @var AuthService
     */
    protected $authService;

    /**
     * AuthController constructor.
     * @param AuthService $authService
     */
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Method to register a user
     * @param Request $request
     * @param RegisterRequest $registerRequest
     * @return JsonResponse
     */
    public function register(Request $request, RegisterRequest $registerRequest)
    {
        try {
            // validate the incoming request
            $registerRequest->validateRequest($request->all());

            DB::beginTransaction();
            $this->authService->register($request);
            DB::commit();

            return response()->json([
                'status' => 200,
                'message' => 'We\'ve sent a verification code to your mobile. Please verify to continue'
            ], 200);
        } catch (ValidationException $exception) {
            return response()->json([
                'status' => 422,
                'errors' => $exception->errors()
            ], 422);
        } catch (RequestException $exception) {
            DB::rollBack();
            return response()->json([
                'status' => $exception->getCode(),
                'message' => $exception->getMessage()
            ], $exception->getCode());
        } catch (\Exception $exception) {
            DB::rollBack();
            return response()->json([
                'status' => 500,
                'message' => $exception->getMessage()
            ], 500);
        }
    }

    /**
     * Method to verify otp and create verification token
     * @param Request $request
     * @return JsonResponse
     */
    public function verifyOtp(Request $request)
    {
        try {
            $this->validate($request, [
                'otp' => 'required'
            ], ['otp.required' => 'The OTP is required']);

            $this->authService->verifyOtp($request);

            return response()->json([
                'status' => 200,
                'message' => 'We\'ve sent you an account activation link. Please check your mail'
            ], 200);
        } catch (ValidationException $exception) {
            return response()->json([
                'status' => 422,
                'errors' => $exception->errors()
            ], 422);
        } catch (ModelNotFoundException $exception) {
            DB::rollBack();
            return response()->json([
                'status' => 404,
                'message' => $exception->getMessage()
            ], 404);
        } catch (\Exception $exception) {
            DB::rollBack();
            return response()->json([
                'status' => 500,
                'message' => $exception->getMessage()
            ], 500);
        }
    }

    /**
     * Method to verify email address
     * @param Request $request
     * @return JsonResponse
     */
    public function verifyToken(Request $request)
    {
        try {
            $this->validate($request, [
                'token' => 'required',
            ], ['token.required' => 'Token is required']);

            $this->authService->verifyToken($request);

            return response()->json([
                'status' => 200,
                'message' => 'Your account is successfully verified. You can now login to med-o-sys'
            ], 200);
        } catch (ValidationException $exception) {
            return response()->json([
                'status' => 422,
                'errors' => $exception->errors()
            ], 422);
        } catch (ModelNotFoundException $exception) {
            DB::rollBack();
            return response()->json([
                'status' => 404,
                'message' => $exception->getMessage()
            ], 404);
        } catch (\Exception $exception) {
            DB::rollBack();
            return response()->json([
                'status' => 500,
                'message' => $exception->getMessage()
            ], 500);
        }
    }

    /**
     * Method to log in the user
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request)
    {
        try {
            $this->validate($request, [
                'username' => 'required',
                'password' => 'required'
            ], ['*.required' => 'This field is required']);

            $credentials = $this->authService->login($request);

            return response()->json([
                'status' => 200,
                'access_token' => $credentials['access_token'],
                'roles' => $credentials['roles'],
                'profile_image' => $credentials["profile_image"]
            ], 200);
        } catch (ValidationException $exception) {
            return response()->json([
                'status' => 422,
                'errors' => $exception->errors()
            ], 422);
        } catch (UnauthorizedException $exception) {
            return response()->json([
                'status' => 401,
                'message' => $exception->getMessage()
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
     * Method to send reset password link
     * @param Request $request
     * @return JsonResponse
     */
    public function sendPasswordResetLink(Request $request)
    {
        try {
            $this->validate($request, [
                'username' => 'required',
                'reset_type' => 'required|in:sms,email'
            ], [
                '*.required' => 'This field is required',
                'reset_type.in' => 'Reset type can be either sms or email only'
            ]);

            $this->authService->sendResetPasswordLink($request);

            $message = ($request->reset_type == 'sms')
                ? "We've sent you an otp to reset your password"
                : "We've sent you an email with reset password link";

            return response()->json([
                'status' => 200,
                'message' => $message
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
     * Method to check whether user verified or not before resetting password
     * @param Request $request
     * @return JsonResponse
     */
    public function checkResetPasswordVerification(Request $request)
    {
        try {
            $this->validate($request, [
                'username' => 'required'
            ], [
                'username.required' => 'Couldn\'t find username in request'
            ]);

            $checkResetPasswordVerification = $this->authService->checkResetPasswordVerification($request);

            assert($checkResetPasswordVerification);

            return response()->json([
                'status'  => 200,
                'message' => 'success'
            ], 200);

        } catch(ValidationException $exception) {
            return response()->json([
                'status' => 422,
                'errors' => $exception->errors()
            ], 422);
        } catch (CustomException $exception) {
            return $exception->getResponse();
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
     * Method to reset password via OTP
     * @param Request $request
     * @return JsonResponse
     */
    public function resetPasswordViaOTP(Request $request)
    {
        try {
            $this->validate($request, [
                'otp' => 'required'
            ], ['otp.required' => 'OTP is required']);

            $user = $this->authService->resetPasswordViaOTP($request);

            return response()->json([
                'status' => 200,
                'data' => $user
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
     * Method to reset password by mail
     * @param Request $request
     * @return JsonResponse
     */
    public function resetPasswordViaEmail(Request $request)
    {
        try {
            $this->validate($request, [
                'token' => 'required'
            ], ['token.required' => 'Token is required']);

            $user = $this->authService->resetPasswordViaEmail($request);

            return response()->json([
                'status' => 200,
                'data' => $user
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
     * Method to set a new password
     * @param Request $request
     * @return JsonResponse
     */
    public function resetPassword(Request $request)
    {
        try {
            $this->validate($request, [
                'email' => 'required|email',
                'password' => 'required|confirmed'
            ], [
                'email.required' => 'The email is required',
                'email.email' => 'The email must be a valid email address',
                'password.required' => 'Password field is required',
                'password.confirmed' => 'Password confirmation didn\'t match',
            ]);

            $this->authService->resetPassword($request);

            return response()->json([
                'status' => 200,
                'message' => 'Your password was successfully reset'
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

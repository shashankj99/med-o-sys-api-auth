<?php

namespace App\Services;

use App\Jobs\ResetPasswordJob;
use App\Jobs\SendActivationMailJob;
use App\Models\Otp;
use App\Models\User;
use App\Models\VerificationToken;
use App\Traits\GetUserAge;
use App\Traits\GetUserImage;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\UnauthorizedException;
use Illuminate\Validation\ValidationException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthService
{
    use GetUserAge, GetUserImage;

    /**
     * @var User
     */
    protected $user;

    /**
     * AuthService constructor.
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Method to register a user
     * @param $request
     * @throws ValidationException
     */
    public function register($request)
    {
        $age = $this->getUserAge($request);

        // create user
        $user = $this->user->create([
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'nep_name' => $request->nep_name,
            'province' => $request->province,
            'district' => $request->district,
            'city' => $request->city,
            'ward_no' => $request->ward_no,
            'dob_ad' => $request->dob_ad,
            'dob_bs' => $request->dob_bs,
            'mobile' => $request->mobile,
            'email' => $request->email,
            'password' => $request->password,
            'age' => $age,
            'blood_group' => $request->blood_group,
            'img' => $this->getImageName($request->img, $request->mobile)
        ]);

        // generate otp if user is created successfully
        $this->createOtp($user, 'activate');
    }

    /**
     * Method to verify otp and create verification token
     * @param $request
     */
    public function verifyOtp($request)
    {
        // get the otp
        $otp = Otp::where('otp', $request->otp)
            ->where('type', 'activate')
            ->first();

        // throw not found error
        if (!$otp)
            throw new ModelNotFoundException('The code that you entered is incorrect');

        // save mobile verification status
        $otp->user->mobile_verification = true;
        $otp->user->save();

        // create the verification token
        $this->createVerificationToken($otp->user, 'activate');

        // delete the otp
        $otp->delete();

        // send mail
        dispatch(new SendActivationMailJob($otp->user));
    }

    /**
     * Method to verify email activation
     * @param $request
     */
    public function verifyToken($request)
    {
        // get the verification token
        $verificationToken = VerificationToken::where('token', $request->token)
            ->andWhere('type', 'activate')
            ->first();

        // throw not found error
        if (!$verificationToken)
            throw new ModelNotFoundException('Unable to find the verification token');

        // save email verification status
        $verificationToken->user->email_verification = true;

        // set status to active
        if ($verificationToken->user->mobile_verification == 1)
            $verificationToken->user->status = true;

        $verificationToken->user->save();

        // delete the verification token
        $verificationToken->delete();
    }

    /**
     * Method to log in the user
     * @param $request
     * @return string
     */
    public function login($request)
    {
        // fetch user with the given mobile or email
        $user = $this->user->where('mobile', $request->username)
            ->orWhere('email', $request->username)
            ->first();

        // throw not found error
        if (!$user)
            throw new ModelNotFoundException('Unable to find the user associated with this mobile number or email address');

        // check if the user is active or not
        if ($user->status != 1)
            throw new UnauthorizedException('You\'ve not activated the account yet');

        // check if the entered password is correct or not
        if (!Hash::check($request->password, $user->password))
            throw new UnauthorizedException('The credentials didn\'t match');

        if ($user->token)
            $token = $user->token->token;
        else {
            $token = JWTAuth::fromUser($user);
            $user->token()->create(['token' => $token]);
        }

        return $token;
    }

    /**
     * Method to send password reset link
     * @param $request
     */
    public function sendResetPasswordLink($request)
    {
        // get the user via mobile number or email address
        $user = $this->user->where('mobile', $request->username)
            ->orWhere('email', $request->username)
            ->first();

        // throw not found error
        if (!$user)
            throw new ModelNotFoundException('Unable to find the user associated with this mobile number or email address');

        // get the reset type
        if ($request->reset_type == 'sms')
            $this->createOtp($user, 'reset');
        else {
            $this->createVerificationToken($user, 'reset');
            dispatch(new ResetPasswordJob($user));
        }
    }

    /**
     * Method to reset password via OTP
     * @param $request
     * @return mixed
     */
    public function resetPasswordViaOTP($request)
    {
        // get the otp data
        $otp = Otp::where('otp', $request->otp)
            ->where('type', 'reset')
            ->first();

        // throw not found exception
        if (!$otp)
            throw new ModelNotFoundException('The OTP has already been expired');

        // delete the otp
        $otp->delete();

        if ($otp->user->token)
            // delete the access token
            $otp->user->token()->delete();

        // return the user
        return $otp->user;
    }

    /**
     * Method to reset password via email
     * @param $request
     * @return mixed
     */
    public function resetPasswordViaEmail($request)
    {
        // get the verification token data
        $verificationToken = VerificationToken::where('token', $request->token)
            ->where('type', 'reset')
            ->first();

        // throw not found exception
        if (!$verificationToken)
            throw new ModelNotFoundException('The reset password token has already expired');

        // delete the token
        $verificationToken->delete();

        if ($verificationToken->user->token)
            // delete the access token
            $verificationToken->user->token()->delete();

        return $verificationToken->user;
    }

    /**
     * Method to set a new password
     * @param $request
     */
    public function resetPassword($request)
    {
        // fetch user via email  address
        $user = $this->user->where('email', $request->email)
            ->first();

        // throw not found error
        if (!$user)
            throw new ModelNotFoundException('Unable to find the user');

        // change the password
        $user->password = $request->password;
        $user->save();
    }

    /**
     * Method to create an OTP
     * @param $user
     * @param $type
     */
    private function createOtp($user, $type)
    {
        // generate a random number of six digit
        $otp = mt_rand(1, 9) . mt_rand(0, 9). mt_rand(0, 9) . mt_rand(0, 9) . mt_rand(0, 9) . mt_rand(0, 9);

        // check if OTP has already been created and still in DB
        if ($user->otp()->where('otp', $otp)->where('type', $type)->first())
            // recursively call the function again
            $this->createOtp($user, $type);
        else
            // create OTP
            $user->otp()->create(['otp' => $otp, 'type' => $type]);
    }

    /**
     * Method to create verification token
     * @param $user
     * @param $type
     */
    private function createVerificationToken($user, $type)
    {
        // get the verification key from config
        $verificationKey = config('app.verification_key');

        // get current date time
        $dateTimeNow = Carbon::now()->toDayDateTimeString();

        // create verification token
        $verificationToken = bin2hex($verificationKey . $dateTimeNow);

        // create verification token
        $user->verificationToken()->create(['token' => $verificationToken, 'type' => $type]);
    }
}

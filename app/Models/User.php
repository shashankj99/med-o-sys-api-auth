<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Hash;
use Laravel\Lumen\Auth\Authorizable;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * Class User
 * @package App\Models
 * @author Shashank Jha
 */
class User extends Model implements AuthenticatableContract, AuthorizableContract, JWTSubject
{
    use Authenticatable, Authorizable, HasFactory, HasRoles;

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'first_name', 'middle_name', 'last_name', 'nep_name', 'province', 'district', 'city', 'ward_no', 'dob_ad',
        'dob_bs', 'mobile', 'email', 'password', 'age', 'blood_group', 'img', 'mobile_verification', 'email_verification',
        'status'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    /**
     * Attribute added to model's JSON form
     * @var array
     */
    protected $appends = ['full_name'];

    // guard name
    protected $guard_name = 'api';

    /**
     * Method to hash a password
     * @param $password
     */
    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = Hash::make($password);
    }

    /**
     * Method to capitalize the initial of first name
     * @param $firstName
     */
    public function setFirstNameAttribute($firstName)
    {
        $this->attributes['first_name'] = ucfirst($firstName);
    }

    /**
     * Method to capitalize the initial of middle name
     * @param $middleName
     */
    public function setMiddleNameAttribute($middleName)
    {
        $this->attributes['middle_name'] = isset($middleName) ? ucfirst($middleName) : null;
    }

    /**
     * Method to capitalize the initial of last name
     * @param $lastName
     */
    public function setLastNameAttribute($lastName)
    {
        $this->attributes['last_name'] = ucfirst($lastName);
    }

    /**
     * Method to get the full link for img avatar
     * @return string
     */
    public function getImgAttribute()
    {
        return config('app.get_avatar_image_url') . $this->attributes['img'];
    }

    /**
     * Method to get the full name of the user
     * @return string
     */
    public function getFullNameAttribute()
    {
        if ($this->attributes['middle_name'] != null)
            return $this->attributes['first_name'] . ' ' . $this->attributes['middle_name'] . ' ' . $this->attributes['last_name'];
        else
            return $this->attributes['first_name'] . ' ' . $this->attributes['last_name'];
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * @return HasOne
     */
    public function token()
    {
        return $this->hasOne(Token::class);
    }

    /**
     * @return HasOne
     */
    public function verificationToken()
    {
        return $this->hasOne(VerificationToken::class);
    }

    /**
     * @return HasOne
     */
    public function otp()
    {
        return $this->hasOne(Otp::class);
    }

    /**
     * @return HasOne
     */
    public function hospital_user()
    {
        return $this->hasOne(HospitalUser::class);
    }

    /**
     * Method to delete the token related to the user
     */
    public static function boot()
    {
        parent::boot();

        self::deleting(function ($user) {
            $user->token()->delete();
        });
    }
}

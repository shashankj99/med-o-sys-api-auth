<?php

namespace App\Http;

class AuthUser
{
    /**
     * @var null
     */
    protected $user = null;

    /**
     * @var null
     */
    protected $accessToken = null;

    /**
     * Set access tken obtained from middleware
     * @param $token
     */
    public function setAccessToken($token)
    {
        $this->accessToken = $token;
    }

    /**
     * Get access token
     * @return null
     */
    public function accessToken()
    {
        return $this->accessToken;
    }

    /**
     * Set user
     * @param $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * Get the user
     * @return null
     */
    public function user()
    {
        return $this->user;
    }

    /**
     * Get the collection of role name attribute of the user
     * @return mixed
     */
    public function roles()
    {
        return $this->user->getRoleNames();
    }

    /**
     * Get the collection of permission name attribute of the user
     * @return mixed
     */
    public function permissions()
    {
        return $this->user->getPermissionNames();
    }

    /**
     * Check whether user has the passed role or not
     * @param array $roles
     * @return bool
     */
    public function hasRoles($roles)
    {
        $_roles = $this->user->getRoleNames()->toArray();

        if (!empty($roles)) {
            foreach ($roles as $role) {
                if (in_array($role, $_roles))
                    return true;
            }
        }

        return false;
    }

}

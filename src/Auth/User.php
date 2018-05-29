<?php

namespace GetCandy\Client\Auth;

use Illuminate\Contracts\Auth\Authenticatable;
use Carbon\Carbon;

class User implements Authenticatable
{
    /**
     * @var string
     */
    protected $id;

    /**
     * @var string
     */
    protected $email;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var mixed
     */
    protected $expires;

    /**
     * @var string
     */
    protected $refresh;

    /**
     * @var string
     */
    protected $type;

    public function __construct($email, $name = null, $token = null, $refresh = null, $expires = null, $type = 'Bearer')
    {
        $this->id = $token;
        $this->email = $email;
        $this->name = $name;
        $this->refresh = $refresh;
        $this->setExpiry($expires);
        $this->type = $type;
    }

    public function getExpiry()
    {
        return $this->expires;
    }

    public function setExpiry($expiry)
    {
        if ($expiry) {
            if ($expiry instanceof Carbon) {
                $this->expires = $expiry;
            } else {
                $this->expires = Carbon::now()->addSeconds($expiry);
            }
        }
    }

    public function setToken($token)
    {
        $this->token = $token;
    }

    public function setRefresh($refresh)
    {
        $this->refresh = $refresh;
    }

    public function setType($type)
    {
        $this->type = $type;
    }

    public function getRefresh()
    {
        return $this->refresh;
    }

    public function getAuthIdentifierName()
    {
        return 'id';
    }

    public function getAuthIdentifier()
    {
        return $this->id;
    }

    public function getAuthPassword()
    {
        return $this->id;
    }

    public function getRememberToken()
    {
        return $this->refresh;
    }

    public function setRememberToken($value)
    {
        $this->refresh = $value;
    }

    public function getRememberTokenName()
    {
        return 'refresh_token';
    }
}

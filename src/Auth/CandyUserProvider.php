<?php

namespace GetCandy\Client\Auth;

use GetCandy\Client\Facades\CandyClient;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Contracts\Auth\Authenticatable;

class CandyUserProvider implements UserProvider
{
    public function retrieveByRefreshToken($token)
    {
        $payload = CandyClient::getRefreshToken($token);

        if ($payload->hasFailed()) {
            return;
        }

        $user = CandyClient::setToken($payload->getBody()->access_token)->users()->current();

        return new User(
            $user->getBody()->email,
            $user->getBody()->name,
            $payload->access_token,
            $payload->refresh_token,
            $payload->expires_in,
            $payload->token_type
        );
    }

    public function retrieveById($identifier)
    {
        $user = CandyClient::setToken($identifier)->users()->current();

        if ($user->hasFailed()) {
            return;
        }

        return new User(
            $user->getBody()->email,
            $user->getBody()->name,
            $identifier
        );
    }

    public function retrieveByCredentials(array $credentials)
    {
        $response = CandyClient::getUserToken($credentials['email'], $credentials['password']);

        if ($response->hasFailed()) {
            return;
        }

        $payload = $response->getBody();

        // Get our user.
        $user = CandyClient::setToken($payload->access_token)->users()->current();

        return new User(
            $user->getBody()->email,
            $user->getBody()->name,
            $payload->access_token,
            $payload->refresh_token,
            $payload->expires_in,
            $payload->token_type
        );
    }

    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        // Always true as it's the API that will be checking.
        return true;
    }

    public function retrieveByToken($identifier, $token)
    {
        //
    }

    public function updateRememberToken(Authenticatable $user, $token)
    {
        //
    }
}

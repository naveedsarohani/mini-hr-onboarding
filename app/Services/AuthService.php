<?php

namespace App\Services;

use App\Models\User;

class AuthService
{
    public function authenticate($credentials)
    {
        $user = User::where('email', $credentials?->email)->first();
        if (!$user) return false;

        $passwordMatch = password_verify($credentials?->password, $user?->password);
        if (!$passwordMatch) return false;

        session()->set('authenticated_user', $user);
        return $user;
    }
}

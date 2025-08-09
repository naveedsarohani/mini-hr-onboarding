<?php

namespace App\Controllers;

use App\Services\AuthService;
use Core\Request;

class AuthController
{
    public function login()
    {
        return view('pages.auth.login');
    }

    public function validateLogin(Request $request, AuthService $service)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (!$service->authenticate($validated)) {
            return redirect()->back()->withToast('error', 'Invalid login credentials!')->withInputs();
        }

        return redirect()->route('employees.index')->withToast('success', 'Login successfully!');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->route('auth.login')->withToast('success', 'You have been logout.');
    }
}

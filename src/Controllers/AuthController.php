<?php

namespace Zahirzohair\Neuracore\Controllers;

use Zahirzohair\Neuracore\Application\Auth\AuthService;
use Zahirzohair\Neuracore\Core\Controller;
use Zahirzohair\Neuracore\Core\Request;
use Zahirzohair\Neuracore\Core\Response;
use Zahirzohair\Neuracore\Core\View;

class AuthController extends Controller
{
    private AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function showLoginForm()
    {
        $html = View::render('auth.login');
        Response::html($html);
    }

    public function login(Request $request)
    {
        $user = $this->authService->attempt(
            $request->input('email'),
            $request->input('password')
        );

        if (!$user) {
            return Response::html('Invalid credentials', 401);
        }

        $_SESSION['user_id'] = $user->id();

        Response::redirect('/');
    }

    public function showRegisterForm()
    {
        echo "Register Form will be here";
    }

    public function register(Request $request)
    {
        $email = $request->input('email');
        $password = $request->input('password');

        echo "Attempting registration for: {$email}";
        // TODO: Add real registration logic
    }
}

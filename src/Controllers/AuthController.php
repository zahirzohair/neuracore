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

    public function showLoginForm(Request $request)
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
        session_regenerate_id(true);

        Response::redirect('/');
    }

    public function showRegisterForm(Request $request)
    {
        $html = View::render('auth.register');
        Response::html($html);
    }

    public function register(Request $request)
    {
        $name = trim((string)$request->input('name', ''));
        $email = $request->input('email');
        $password = $request->input('password');

        if (!$email || !$password) {
            return Response::html('Missing email or password', 422);
        }

        if ($name === '') {
            $name = 'User';
        }

        $user = $this->authService->register($name, $email, $password);

        if (!$user) {
            return Response::html('Email already registered', 409);
        }

        $_SESSION['user_id'] = $user->id();
        session_regenerate_id(true);
        Response::redirect('/');
    }

    public function logout(Request $request): void
    {
        $_SESSION = [];
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_destroy();
        }

        Response::redirect('/login');
    }
}

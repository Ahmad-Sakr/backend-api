<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\LoginRequest;
use App\Http\Requests\v1\RegisterRequest;
use App\Interfaces\AuthInterface;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    protected $authInterface;

    public function __construct(AuthInterface $authInterface)
    {
        $this->authInterface = $authInterface;
    }

    public function register(RegisterRequest $request)
    {
        return $this->authInterface->register($request);
    }

    public function login(LoginRequest $request)
    {
        return $this->authInterface->login($request);
    }

    public function getLoggedInUser(Request $request)
    {
        return $this->authInterface->getLoggedInUser($request);
    }

    public function logout(Request $request)
    {
        return $this->authInterface->logout($request);
    }
}

<?php


namespace App\Interfaces;


use App\Http\Requests\v1\LoginRequest;
use App\Http\Requests\v1\RegisterRequest;
use Illuminate\Http\Request;

interface AuthInterface
{
    public function register(RegisterRequest $request);

    public function login(LoginRequest $request);

    public function logout(Request $request);

    public function getLoggedInUser(Request $request);
}

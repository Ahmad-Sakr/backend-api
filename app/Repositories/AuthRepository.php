<?php


namespace App\Repositories;


use App\Helpers\ResponseMessages;
use App\Http\Requests\v1\LoginRequest;
use App\Http\Requests\v1\RegisterRequest;
use App\Http\Resources\v1\UserResource;
use App\Interfaces\AuthInterface;
use App\Models\Role;
use App\Models\User;
use App\Traits\ApiExceptionHandler;
use App\Traits\ApiResponder;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Validator;

class AuthRepository implements AuthInterface
{
    use ApiResponder, ApiExceptionHandler;

    public function register(RegisterRequest $request)
    {
        DB::beginTransaction();
        try {
            $data = $request->validated();
            $data['password'] = bcrypt($data['password']);

            //Create User
            $user = User::query()->create($data);

            //Save Role
            $role = Role::query()->where('name', 'User')->first();
            $user->roles()->attach($role->id);

            DB::commit();
            return $this->success([
                        'token' => $user->createToken($user->username)->plainTextToken,
                        'user'  => (new UserResource($user))
                    ],ResponseMessages::REGISTERED, Response::HTTP_CREATED);
        } catch(Exception $e) {
            DB::rollBack();
            return $this->handleException($e);
        }
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();

        if (!Auth::attempt($credentials)) {
            throw ValidationException::withMessages([
                'username' => ['The provided credentials are incorrect']
            ]);
        }

        //Return User With Token
        return $this->success([
                    'token' => Auth::user()->createToken($credentials['username'])->plainTextToken,
                    'user'  => (new UserResource(Auth::user()))
                ],ResponseMessages::LOGGED_IN, Response::HTTP_OK);
    }

    public function logout(Request $request)
    {
        $user = $request->user();
        $user->tokens()->delete();
        return $this->success([], 'Tokens are deleted.');
    }

    public function getLoggedInUser(Request $request)
    {
        return new UserResource($request->user());
    }
}

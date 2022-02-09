<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\UserRegistrationRequest;
use App\Http\Requests\UserForgotPasswordRequest;
use App\Http\Requests\UserResetPasswordRequest;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    use SendsPasswordResetEmails;

    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register', 'forgotPassword', 'resetUserPassword']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request)
    {
        $input = $request->validated();

        $credentials = [
            'email' => $input['email'],
            'password' => $input['password'],
        ];

        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Invalid email or password'], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }

    /**
     * Creates a new user from the registration page
     *
     * @param UserRegistrationRequest $request
     * @return string json
     */
    public function register(UserRegistrationRequest $request)
    {
        $input = $request->validated();

        $data = [
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
        ];

        $errMsg = null;

        try {

            $createdUser = User::create($data);

            if ($createdUser->exists) {
                return response()->json([
                    'success' => true,
                    'message' => 'Success on user registration'
                ], 201);
            }

        } catch (\Illuminate\Database\QueryException $e) {

            $errMsg = $e->getMessage();

        }

        return response()->json([
            'success' => false,
            'message' => 'User registration error',
            // 'debug' => $errMsg,
        ], 200);

    }

    /**
     * Send an email to the user to set the new password
     *
     * @param UserForgotPasswordRequest $request
     * @return string json
     */
    public function forgotPassword(UserForgotPasswordRequest $request)
    {
        $input = $request->validated();

        $data = ['email' => $input['email']];
        $statusCode = 200;

        try {

            // trait
            $response = $this->sendResetLinkEmail($request);

            $statusCode = !empty($response->statusCode) ? $response->statusCode : $statusCode;

            $responseData = [
                'success' => $statusCode == 200,
                'message' => $response->original['message'],
            ];

        } catch (\Exception $e) {

            $responseData = [
                'success' => false,
                'message' => 'internal server error',
            ];

        }

        return response()->json($responseData, $statusCode);

    }

    /**
     * Resets users password
     *
     * @param UserResetPasswordRequest $request
     * @return string json
     */
    public function resetUserPassword(UserResetPasswordRequest $request)
    {

        $credentials = $request->only(
            'email', 'password', 'password_confirmation', 'token'
        );

        $response = $this->broker()->reset(
            $credentials, function ($user, $password) {

                $user->password = Hash::make($password);

                $user->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));

            }
        );

        $responseCode = $response == 'password.reset' ? 200 : 422;

        $responseData = [
            'success' => $response == 'password.reset',
            'message' => '',
        ];

        $responseData['message'] = trans($response);

        return response()->json($responseData, $responseCode);

    }

}

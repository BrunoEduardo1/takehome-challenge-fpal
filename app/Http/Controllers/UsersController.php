<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserUpdateRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UsersController extends Controller
{

    public function create(UserCreateRequest $request)
    {
        $request = $request->validated();

        $data = [
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
        ];

        $errMsg = null;

        try {

            $createdUser = User::create($data);

            if ($createdUser->exists) {
                return response()->json([
                    'success' => true,
                    'message' => trans('success'),
                    'item_id' => $createdUser->id,
                ], 201);
            }

        } catch (\Illuminate\Database\QueryException $e) {

            $errMsg = $e->getMessage();

        }

        return response()->json([
            'success' => false,
            'message' => trans('error'),
            // 'debug' => $errMsg,
        ], 200);

    }

    public function getById(int $userId)
    {
        $user = User::find($userId)->makeHidden(['password', 'updated_at', 'email_verified_at', 'remember_token']);

        if ($user) {
            return response()->json([
                'success' => true,
                'message' => 'ok',
                'item' => $user
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => trans('not_found'),
        ], 404);
    }

    public function all()
    {
        $usersDb = User::select(DB::raw('id, name, email, created_at, DATE_FORMAT(created_at, "%d\/%m\/%Y") as formatted_created_at'))
            ->orderBy('id', 'DESC')
            ->paginate(10);

        return response()->json([
            'success' => true,
            'message' => 'ok',
            'items' => $usersDb,
        ], 200);

    }

    public function update(UserUpdateRequest $request, int $userId)
    {
        $data = $request->validated();

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        $updatedUser = (int) User::where('id', $userId)->update($data);

        if ($updatedUser) {
            return response()->json([
                'success' => true,
                'message' => trans('success'),
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => trans('error'),
        ], 422);
    }

    public function delete(int $userId)
    {
        $deletedUser = (int) User::destroy($userId);

        if ($deletedUser) {
            return response()->json([
                'success' => true,
                'message' => trans('success'),
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => trans('error'),
        ], 404);

    }

}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Validator;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'confirmation_password' => 'required|same:password',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        $post = $request->all();
        $post['password'] = Hash::make($post['password']);

        DB::beginTransaction();
        try {
            $user = User::create($post);

            DB::commit();
            return response()->json([
                'status'    => 'success',
                'result'    => [
                    'user'  => $user,
                    'token' => $user->createToken('user')->accessToken
                ]
            ], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status'    => 'fail',
                'messages'  => 'Something Went Wrong!'
            ], 400);
        }
    }

    public function login(Request $request)
    {
        $post = $request->all();
        if (Auth::attempt(['email' => $post['email'], 'password' => $post['password']])) {
            $user = Auth::user();

            return response()->json([
                'status'    => 'success',
                'result'    => [
                    'user'  => $user,
                    'token' => $user->createToken('user')->accessToken
                ]
            ], 200);
        } else {
            return response()->json([
                'status'    => 'fail',
                'messages'  => 'Something Went Wrong!'
            ], 400);
        }
    }
}

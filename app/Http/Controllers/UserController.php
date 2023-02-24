<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function login(Request $request)
    {
        $this->validate(
            $request,
            [
                'email' => 'required|string|max:255',
                'password' => 'required|string|max:255',
            ],
            [
                'email.required' => 'email is required',
                'email.max' => 'email: max 255 characters',
                'password.required' => 'password is required',
                'email.max' => 'password max 255 characters',
            ]
        );


        $credentials = [
            'email' => $request->input('email'),
            'password' => $request->input('password')
        ];

        if (!$token = Auth::attempt($credentials)) {
            return response()->json(['error' => 'Email or password not correct!!!'], 401);
        }

        // Auth::user()->update(['api_token' => $token]);
        Auth::user()->api_token = $token;
        Auth::user()->save();
        return $this->respondWithToken($token);
    }

    public function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::factory()->getTTL() * 60
        ]);
    }

    public function register(Request $request)
    {
        $data = $this->validate($request, [
            'email' => 'bail|string|unique:users,email|max:255',
            'password' => 'bail|string|max:255',
            'roles' => 'array|exists:roles,id'
        ], [
            'roles.exists' => 'role(s) selected not exist'
        ]);
        DB::beginTransaction();
        try {
            $user = User::create([
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                // . . .
            ]);
            if ($data['roles']) {
                $user->attachRoles($data['roles']);
            } else {
                $user->attachRoles([9]);
            }
            DB::commit();
            return response([
                'message' => 'register successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            // throw new \Exception('Can not register!');
            return $e;
        }
    }

    public function listUsers(){
        return User::with('roles')->paginate(10);
    }

    public function detailUser($id) {
        return User::where('id', $id)->with('roles')->first();
    }

    public function logout()
    {
        Auth::user()->api_token = null;
        Auth::user()->save();

        return response(['message' => 'Logout successfully']);
    }

    public function showInfo()
    {
        $user = Auth::user();
        if (!$user) {
            throw new \Exception("Something went wrong!");
        }
        return $user->load('roles');
    }

    public function updateInfo(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            throw new \Exception("Something went wrong!");
        }
        $data = $this->validate(
            $request,
            [
                'first_name' => 'max:255',
                'last_name' => 'max:255',
                'phone1' => 'regex:/(0)[0-9]{9}/',
                'phone2' => 'regex:/(0)[0-9]{9}/',
                'city' => 'max:255',
                'country' => 'max:255',
                'address' => 'max:255',
            ]
        );
        try {
            $user->first_name = $data['first_name'];
            $user->last_name = $data['last_name'];
            $user->phone1 = $data['phone1'];
            $user->phone2 = $data['phone2'];
            $user->city = $data['city'];
            $user->country = $data['country'];
            $user->address = $data['address'];
            $user->save();

            return response([
                'message' => 'Update profile successfully'
            ]);
        } catch (\Exception $e) {
            throw new \Exception('Something went wrong!');
        }
    }


    public function updateRoles(Request $request, $id)
    {
        $user = User::findOrFail($id);
        if($user->email == $request->email){
            $data = $this->validate($request, [
                'roles' => 'exists:roles,id'
            ], [
                'roles.exists' => 'roles selected not exist'
            ]);
        }
        else {
            $data = $this->validate($request, [
                'email' => 'unique:users,email',
                'roles' => 'exists:roles,id'
            ], [
                'roles.exists' => 'roles selected not exist'
            ]);
        }
        try {
            DB::beginTransaction();
            
            $user->email = $data['email'] ?? $user->email;
            $user->save();
            $user->updateRoles($data['roles']);
            DB::commit();
            return response([
                'message' => 'Update successfully'
            ]);
        } catch (\Exception $th) {
            DB::rollBack();
            // throw new \Exception('Something went wrong!');
            return $th;
        }
    }
}

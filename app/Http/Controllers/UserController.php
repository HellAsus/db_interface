<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\User as UserRes;

class UserController extends Controller
{
    public function getUsers ()
    {
        return UserRes::collection(User::all());
    }
    public function createUsers (Request $request)
    {
        $data = $request->only('login', 'fio', 'password', 'role', 'password_confirmation');
        $validator = Validator::make($request->all(), [
            'login' => 'required|string|min:6|max:16|unique:users',
            'fio' => 'required|string|max:255',
            'password' => 'required|string|min:6|max:16|confirmed',
        ]);
        if(!$validator->fails())
        {
           $id =  User::insertGetId([
                'login' => $data['login'],
                'fio' => $data['fio'],
                'password' => Hash::make($data['password']),
            ]);

            $perm = new PermissionController();
            $perm->setRoles($id, $data['role']);
            return new UserRes(User::find($id));
        }
        else {
            return response()->json(['error' => $validator->errors()]);
        }
    }
    public function deleteUsers($id)
    {
        User::find($id)->delete();
        return;
    }
}
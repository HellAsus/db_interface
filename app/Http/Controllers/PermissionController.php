<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Http\Resources\Perms as PermRes;

class PermissionController extends Controller
{
    public function createRole (Request $request)
    {
        $role = Role::create(['name' => $request->get('name')]);
        return response()->json(['message' => $role]);
    }

    public function createPermission (Request $request)
    {
        $permission = Permission::create(['name' => $request->get('name')]);
        return response()->json(['message' => $permission]);
    }

    public function givePermission (Request $request)
    {
        $role = Role::findByName($request->get('role'));
        $permission = Permission::findByName($request->get('perm'));
        $result = $role->givePermissionTo($permission);
        return response()->json(['message' => $result]);
        
    }

    public function getRoles ()
    {
        return response()->json(['roles' => Role::all()->pluck('name')]);
        //return PermRes::collection(Role::all());
    }

    public function setRoles ($userId, $role)
    {
        $user = User::find($userId);
        return $user->assignRole($role);
    }

    public function delRole (Request $request)
    {
        Role::findByName($request->get('name'))->delete();
    }

    public function delPermission (Request $request)
    {
        Permission::findByName($request->get('name'))->delete();
    }
}

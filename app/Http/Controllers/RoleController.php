<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    public function index()
    {
        try {
            return Role::with('permissions')->get();
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function detail($id)
    {
        try {
            return Role::with('permissions')->where('id', $id)->first();
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function create(Request $request)
    {
        $data = $this->validateData($request);
        try {
            Role::create([
                'name' => $data->name,
            ]);
            return response([
                'message' => 'Create role successfully'
            ]);
        } catch (\Exception $ex) {
            throw new \Exception('Can not create role!');
        }
    }

    public function update(Request $request, $id)
    {
        $data = $this->validateData($request);
        try {
            Role::where('id', $id)
                ->update([
                    'name' => $data->name,
                ]);

            return response(['message' => 'Update role successfully']);
        } catch (\Exception $ex) {
            throw new \Exception('Can not update role!');
        }
    }
    public function delete($id)
    {
        if (DB::table('role_permissions')->where('role_id', $id)->exists()) {
            return response([
                'message' => 'Can not remove because this role used'
            ]);
        }
        try {
            $role = Role::findOrFail($id);
            $role->delete();
            return response('Delete role successfully');
        } catch (\Exception $e) {
            throw new \Exception('role not found!');
        }
    }

    public function addPermissionForRoles(Request $request, $roleId)
    {
        $data = $this->validate($request, [
            'permission_ids' => 'array|exists:permissions,id'
        ], [
            'permission_ids.exists' => 'permission selected not exist'
        ]);
        try {
            $role = Role::findOrfail($roleId);

            $role->addPermissions($data['permission_ids']);

            return response([
                'message' => 'add permission for roles successfully'
            ]);
        } catch (\Exception $e) {
            // throw new \Exception('Something went wrong!');
            return $e;
        }
    }

    private function validateData($request)
    {
        $this->validate(
            $request,
            [
                'name' => 'required:max:255|unique:roles,name',
            ],
            [
                'name.required' => 'You need to input name',
                'name.max' => 'Your name must less than 255 words',
            ]
        );
        return $request;
    }
}

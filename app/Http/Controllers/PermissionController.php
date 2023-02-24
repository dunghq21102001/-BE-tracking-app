<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PermissionController extends Controller
{
    public function index()
    {
        try {
            return Permission::select('id', 'name')->get();
        } catch (\Exception $e) {
            throw $e;
        }
    }
    public function create(Request $request)
    {
        $data = $this->validateData($request);
        try {
            Permission::create([
                'name' => $data->name,
            ]);
            return response([
                'message' => 'Create permission successfully'
            ]);
        } catch (\Exception $ex) {
            throw new \Exception('Can not create permission!');
        }
    }

    public function update(Request $request, $id)
    {

        $data = $this->validateData($request);
        try {
            Permission::where('id', $id)
                ->update([
                    'name' => $data->name,
                ]);

            return response('Update permission successfully');
        } catch (\Exception $ex) {
            throw new \Exception('Can not update permission!');
        }
    }
    public function delete($id)
    {
        if(DB::table('role_permissions')->where('permission_id', $id)->exists()){
            return response([
                'message'=> 'Can not remove because this permission used'
            ]);
        }
        try {
            $permission = Permission::findOrFail($id);
            $permission->delete();
            return response('Delete permission successfully');
        } catch (\Exception $e) {
            throw new \Exception('permission not found!');
        }
    }

    private function validateData($request)
    {
        $this->validate(
            $request,
            [
                'name' => 'required:max:255|unique:permissions,name',
            ],
            [
                'name.required' => 'You need to input name',
                'name.max' => 'Your name must less than 255 words',
            ]
        );
        return $request;
    }
}

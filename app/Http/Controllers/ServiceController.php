<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index()
    {
        return Service::paginate(20);
    }
    public function create(Request $request)
    {
        $data = $this->validateData($request);
        try {
            Service::create([
                'name' => $data->name,
                'description' => $data->description,
            ]);
            return response([
                'message' => 'Create service successfully'
            ]);
        } catch (\Exception $ex) {
            throw new \Exception('Can not create service!');
        }
    }

    public function update(Request $request, $id)
    {
        $data = $this->validateData($request);
        try {
            Service::where('id', $id)
                ->update([
                    'name' => $data->name,
                    'description' => $data->description,
                ]);
            return response([
                'message' => 'Update service successfully'
            ]);
        } catch (\Exception $e) {
            //throw $th;
            return $e;
        }
    }

    public function delete($id)
    {
        try {
            Service::where('id', $id)
                ->delete();
            return response([
                'message' => 'Delete service successfully'
            ]);
        } catch (\Exception $e) {
            //throw $th;
            return $e;
        }
    }

    private function validateData($request)
    {
        $this->validate(
            $request,
            [
                'name' => 'max:255',
                'description' => 'required:max:255',
            ],
            [
                'description.required' => 'You need to input description',
                'name.max' => 'Your name must less than 255 words',
            ]
        );
        return $request;
    }
}

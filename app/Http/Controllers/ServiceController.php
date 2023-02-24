<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index(){
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

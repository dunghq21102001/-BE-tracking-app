<?php

namespace App\Http\Controllers;

use App\Models\Request;
use Illuminate\Http\Request as HttpRequest;

// use Illuminate\Http\Request;

class RequestController extends Controller
{
    public function index()
    {
        return Request::paginate(20);
    }
    public function create(HttpRequest $request)
    {
        // $data = $this->validateData($request);
        try {
            Request::create([
                'full_name' => $request->input('fullName'),
                'email' => $request->input('email'),
                'title' => $request->input('title'),
                'question' => $request->input('question')
            ]);
            return response([
                'message' => 'Create request successfully'
            ]);
        } catch (\Exception $ex) {
            throw new \Exception('Can not create request!');
        }
    }

    public function delete($requestId)
    {
        try {
            Request::where('id', $requestId)->delete();
            return response(['message' => 'Canceled request successfully']);
        } catch (\Exception $e) {
            throw new \Exception('request not found!');
        }
    }

    private function validateData($request)
    {
        $this->validate(
            $request,
            [
                'full_name' => 'max:255',
                'email' => 'max:255',
                'title' => '',
                'question' => 'max:2555'
            ],
            []
        );
        return $request;
    }
}

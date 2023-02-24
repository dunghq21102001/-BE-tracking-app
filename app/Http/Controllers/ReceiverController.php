<?php

namespace App\Http\Controllers;

use App\Models\Receiver;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReceiverController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if (!$user) {
            throw new \Exception("Something went wrong!");
        }
        return Receiver::where('user_id', $user->id)->paginate(20);
    }

    public function detail($id)
    {
        return Receiver::where('id', $id)->first();
    }

    public function create(Request $request)
    {
        $user = Auth::user();
        $data = $this->validateData($request);
        try {
            Receiver::create([
                'first_name' => $data->first_name,
                'last_name' => $data->last_name,
                'phone1' => $data->phone1,
                'phone2' => $data->phone2,
                'address' => $data->address,
                'city' => $data->city,
                'country' => $data->country,
                'user_id' => $user->id,
            ]);
            return response([
                'message' => 'Create receiver successfully'
            ]);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $data = $this->validateData($request);
        try {
            Receiver::where('id', $id)
                ->update([
                    'first_name' => $data->first_name,
                    'last_name' => $data->last_name,
                    'phone1' => $data->phone1,
                    'phone2' => $data->phone2,
                    'address' => $data->address,
                    'city' => $data->city,
                    'country' => $data->country,
                    'user_id' => $user->id,
                ]);
            return response([
                'message' => 'Updated receiver successfully'
            ]);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function delete($id)
    {
        try {
            $receiver = Receiver::findOrFail($id);
            $receiver->delete();
            return response(['message' => 'Delete receiver successfully']);
        } catch (\Exception $e) {
            throw new \Exception('receiver not found!');
        }
    }


    private function validateData($request)
    {
        $this->validate(
            $request,
            [
                'first_name' => 'max:255',
                'last_name' => 'max:255',
                'phone1' => 'regex:/(0)[0-9]{9}/',
                'phone2' => 'regex:/(0)[0-9]{9}/',
                'city' => 'max:255',
                'country' => 'max:255',
                'address' => 'max:255',
            ],
            [
                // 'last_name.required' => 'You need to input name',
                // 'last_name.max' => 'Your name must less than 255 words',
            ]
        );
        return $request;
    }
}

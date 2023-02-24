<?php

namespace App\Http\Controllers;

use App\Models\Tracking;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TrackingController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if (!$user) {
            throw new \Exception("Something went wrong!");
        }
        $trackingList = Tracking::where('user_id', $user->id)->get();
        foreach ($trackingList as $tracking) {
            $tracking['receiver'] = $tracking->receiver;
        }
        return $trackingList;
    }

    public function detail($id)
    {
        return Tracking::with(['user', 'receiver'])->findOrFail($id);
    }

    public function create(Request $request)
    {
        $user = Auth::user();
        // $data = $this->validateData($request);
        try {
            Tracking::create([
                'user_id' => $user->id,
                'receiver_id' => $request->receiver_id,
                'bol_id' => $request->bol_id,
                'note' => $request->note,
                'status' => 'new',
                'delivery_note' => 'none'
            ]);
            return response([
                'message' => 'Create tracking successfully'
            ]);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();
        try {
            Tracking::where('id', $id)
                ->update([
                    'user_id' => $user->id,
                    'receiver_id' => $request->receiver_id,
                    'bol_id' => $request->bol_id,
                    'note' => $request->note,
                    'status' => $request->status,
                    'delivery_note' => 'none'
                ]);
            return response([
                'message' => 'Updated tracking successfully'
            ]);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function delete($id)
    {
        try {
            Tracking::where('id', $id)->update(['status' => 'canceled']);
            return response(['message' => 'Canceled tracking successfully']);
        } catch (\Exception $e) {
            throw new \Exception('tracking not found!');
        }
    }


    private function validateData($request)
    {
        $this->validate(
            $request,
            [],
            []
        );
        return $request;
    }
}

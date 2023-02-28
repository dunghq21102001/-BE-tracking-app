<?php

namespace App\Http\Controllers;

use App\Models\Guild;
use Illuminate\Http\Request;

class GuildController extends Controller
{
    public function index()
    {
        return Guild::paginate(20);
    }
    public function create(Request $request)
    {
        $data = $this->validateData($request);
        try {
            Guild::create([
                'question' => $data->question,
                'answer' => $data->answer,
            ]);
            return response([
                'message' => 'Create guild successfully'
            ]);
        } catch (\Exception $ex) {
            throw new \Exception('Can not create guild!');
        }
    }

    public function update(Request $request, $id)
    {
        $data = $this->validateData($request);
        try {
            Guild::where('id', $id)
                ->update([
                    'question' => $data->question,
                    'answer' => $data->answer,
                ]);
            return response([
                'message' => 'Update guild successfully'
            ]);
        } catch (\Exception $e) {
            //throw $th;
            return $e;
        }
    }

    public function delete($id)
    {
        try {
            Guild::where('id', $id)
                ->delete();
            return response([
                'message' => 'Delete guild successfully'
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
                'question' => 'required|max:255',
                'answer' => 'required|max:5000'
            ],
            [
                'question.required' => 'You need to input question',
                'question.max' => 'Your question must less than 255 words',
                'answer.max' => 'Your answer must less than 255 words',
            ]
        );
        return $request;
    }
}

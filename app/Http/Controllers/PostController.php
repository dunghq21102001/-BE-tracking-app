<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function index()
    {
        return Post::with('user')->paginate(20);
        // latest()->take(5)->get();
    }

    public function getTop5()
    {
        return Post::with('user')->latest()->take(5)->get();
    }

    public function detail($id)
    {
        return Post::with('user')->findOrFail($id);
    }

    public function create(Request $request)
    {
        // return $request;
        $data = $this->validateData($request);
        $user = Auth::user();
        try {
            if ($request->hasFile('file')) {

                $data->file->store('posts', 'public');

                Post::create([
                    'title' => $data->title,
                    'description' => $data->description,
                    'content' => $data->content,
                    'summary' => $data->summary,
                    'file_path' => $request->file->hashName(),
                    'user_id' => $user->id
                ]);
                return response([
                    'message' => 'Create post successfully'
                ]);
            }
            return response([
                'message' => 'Create post fail'
            ]);
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function update(Request $request, $id)
    {
        // return $request;
        $data = $this->validateData($request);
        $user = Auth::user();
        try {
            if ($request->hasFile('file')) {

                $data->file->store('posts', 'public');

                Post::where('id', $id)->update([
                    'title' => $data->title,
                    'description' => $data->description,
                    'content' => $data->content,
                    'summary' => $data->summary,
                    'file_path' => $request->file->hashName(),
                    'user_id' => $user->id
                ]);
                return response([
                    'message' => 'Update post successfully'
                ]);
            }
            return response([
                'message' => 'Update post fail'
            ]);
        } catch (\Exception $e) {
            return $e;
        }
    }


    public function delete($id)
    {
        try {
            $post = Post::findOrFail($id);
            $post->delete();
            return response(['message' => 'Xoá post thành công']);
        } catch (\Exception $e) {
            throw new \Exception('post not found!');
        }
    }


    private function validateData($request)
    {
        $this->validate(
            $request,
            [
                'title' => 'required|max:255',
                'description' => 'max:255',
                'content' => 'max:2000',
                'summary' => 'max:255',
                'image' => 'mimes:png,jpg,bmp,jpeg'
            ],
            [
                // 'last_name.required' => 'You need to input name',
                // 'last_name.max' => 'Your name must less than 255 words',
            ]
        );
        return $request;
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\File;
use App\Models\Image;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function index()
    {
        return Post::with(['user', 'images'])->paginate(20);
        // latest()->take(5)->get();
    }

    public function getTop5()
    {
        return Post::with(['user', 'images'])->latest()->take(5)->get();
    }

    public function detail($id)
    {
        return Post::with(['user', 'images'])->findOrFail($id);
    }

    public function increaseView($id)
    {
        try {
            $post = Post::findOrFail($id);
            $post->count += 1;
            $post->save();
            return response([
                'message' => 'successfully'
            ]);
        } catch (\Throwable $e) {
            return $e;
        }
    }

    public function create(Request $request)
    {
        $data = $this->validateData($request);
        $user = Auth::user();
        $files = $request->file('files');
        try {
            if ($request->hasFile('files')) {

                $post = Post::create([
                    'title' => $data->title,
                    'description' => $data->description,
                    'content' => $data->content,
                    'summary' => $data->summary,
                    'user_id' => $user->id
                ]);
                $i = 0;
                foreach ($files as $file) {
                    $i++;
                    $file_path = $post->id . $i . time() . '.' . $file->getClientOriginalExtension();
                    $file->move('images/', $file_path);
                    Image::create([
                        'file_path' => $file_path,
                        'post_id' => $post->id
                    ]);
                }

                return response([
                    'message' => 'Create post successfully'
                ]);
            }
            return response([
                'message' => 'Create post fail, Please try again!'
            ]);
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function update(Request $request, $id)
    {
        $data = $this->validateData($request);
        $user = Auth::user();
        $files = $request->file('files');
        // return response($request);
        try {
            if ($request->hasFile('files')) {
                $post = Post::where('id', $id)->first();
                $images =  $post->images;
                if (count($images) != 0) {
                    foreach ($images as $image) {
                        Image::where('id', $image->id)->delete();
                        $file_path = public_path() . "/images/" . $image->file_path;
                        File::delete($file_path);
                    }
                }
                $post->update([
                    'title' => $data->title,
                    'description' => $data->description,
                    'content' => $data->content,
                    'summary' => $data->summary,
                    'user_id' => $user->id
                ]);
                $i = 0;
                foreach ($files as $file) {
                    $i++;
                    $file_path = $post->id . $i . time() . '.' . $file->getClientOriginalExtension();
                    $file->move('images/', $file_path);
                    Image::create([
                        'file_path' => $file_path,
                        'post_id' => $post->id
                    ]);
                }

                return response([
                    'message' => 'Update post successfully'
                ]);
            }
            return response([
                'message' => 'Update post fail, Please try again!'
            ]);
        } catch (\Exception $e) {
            return $e;
        }
    }


    public function delete($id)
    {
        try {
            $post = Post::findOrFail($id);
            $images = $post->images;
            foreach ($images as $image) {
                $file_path = public_path() . "/images/" . $image->file_path;
                File::delete($file_path);
            }
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
                'title' => 'max:255',
                'description' => 'max:255',
                'content' => 'max:2000',
                'summary' => 'max:255',
                // 'files' => 'mimes:png,jpg,bmp,jpeg'
            ],
            [
                // 'last_name.required' => 'You need to input name',
                // 'last_name.max' => 'Your name must less than 255 words',
            ]
        );
        return $request;
    }
}

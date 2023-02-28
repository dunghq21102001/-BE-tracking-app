<?php

namespace App\Http\Controllers;

use App\Models\Language;
use Illuminate\Http\Request;

class LanguageController extends Controller
{
    public function index()
    {
        try {
            return Language::paginate(40);
        } catch (\Exception $th) {
            //throw $th;
            return $th;
        }
    }

    public function getEn()
    {
        try {
            return Language::pluck('en');
        } catch (\Exception $th) {
            //throw $th;
            return $th;
        }
    }

    public function getVi()
    {
        try {
            return Language::pluck('vi');
        } catch (\Exception $th) {
            //throw $th;
            return $th;
        }
    }

    public function create(Request $request)
    {
        $this->validate($request, [
            'vi' => 'max:255|required',
            'en' => 'max:255|required'
        ]);
        try {
            Language::create([
                'vi' => $request->vi,
                'en' => $request->en
            ]);
            return response([
                'message' => 'Create the translation successfully'
            ]);
        } catch (\Exception $e) {
            //throw $th;
            return $e;
        }
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'vi' => 'max:255|required',
            'en' => 'max:255|required'
        ]);
        try {
            $lang = Language::findOrFail($id);

            $lang->en = $request->en;
            $lang->vi = $request->vi;
            return response([
                'message' => 'Update the translation successfully'
            ]);
        } catch (\Exception $e) {
            //throw $th;
            return $e;
        }
    }

    public function delete($id)
    {
        try {
            $lang = Language::findOrFail($id);
            $lang->delete();
            return response([
                'message' => 'Delete the translation successfully'
            ]);
        } catch (\Exception $th) {
            //throw $th;
            return $th;
        }
    }
}

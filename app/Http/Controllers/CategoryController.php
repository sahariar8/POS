<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function CategoryList(Request $request)
    {
        $user_id = $request->header('id');
        return Category::where('user_id', $user_id)->get();
    }

    public function CategoryCreate(Request $request)
    {
        $user_id = $request->header('id');
        return Category::create([
            'name' => $request->input('name'),
            'user_id' => $user_id
        ]);
    }

    public function CategoryUpdate(Request $request)
    {

        $user_id = $request->header('id');

        $category_id = $request->input('id');
        $name = $request->input('name');

        return Category::where('id',$category_id)->where('user_id',$user_id)->update([
            'name'=> $name]);
    }

    public function CategoryDelete(Request $request)
    {
        $user_id = $request->header('id');
        $category_id = $request->input('id');

        return Category::where('id', $category_id)->where('user_id', $user_id)->delete();
    }

    public function CategoryByID(Request $request)
    {
        $user_id = $request->header('id');
        $category_id = $request->input('id');
        return Category::where('id', $category_id)->where('user_id', $user_id)->first();
    }

}

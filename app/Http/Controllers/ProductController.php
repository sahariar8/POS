<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function ProductList(Request $request)
    {
        $user_id=$request->header('id');
        return Product::where('user_id',$user_id)->get();
    }

    public function CreateProduct(Request $request)
    {
        $user_id=$request->header('id');
        return Product::create([
            'name'=>$request->input('name'),
            'price'=>$request->input('price'),
            'unit'=>$request->input('unit'),
            'category_id'=>$request->input('category_id'),
            'user_id'=>$user_id
        ]);

    }

    public function UpdateProduct(Request $request)
    {
        $user_id=$request->header('id');
        $product_id=$request->input('id');

        return Product::where('id',$product_id)->where('user_id',$user_id)->update([
            'name'=>$request->input('name'),
            'price'=>$request->input('price'),
            'unit'=>$request->input('unit'),
            'category_id'=>$request->input('category_id')
        ]);

    }

    public function DeleteProduct(Request $request)
    {
        $user_id=$request->header('id');
        $product_id=$request->input('id');
        return Product::where('id',$product_id)->where('user_id',$user_id)->delete();
    }

    public function ProductByID(Request $request)
    {
        $user_id=$request->header('id');
        $product_id=$request->input('id');
        return Product::where('id',$product_id)->where('user_id',$user_id)->first();


    }

}

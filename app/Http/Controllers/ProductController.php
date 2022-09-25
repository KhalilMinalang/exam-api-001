<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // return Product::paginate(5);
        return auth()->user()->products()->paginate(5);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // return auth()->user()->id;
        $request->validate([
            'name' => 'required',
            'image_link' => 'required',
            'price' => 'required',
            // 'is_published' => 'required|boolean'
        ]);

        $product = new Product;
        $product->name = $request->name;
        $product->user_id = auth()->user()->id;
        $product->image_link = $request->image_link;
        $product->price = $request->price;
        $product->is_published = $request->is_published ? $request->is_published : false;
        $product->save();
        return $product;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // return Product::find($id);
        $product = auth()->user()->products->where('id', $id)->first();
        if ($product) {
            return $product;
        }
        // return "Product not found or does not belong to the current authenticated user";
        return response([
                'message' => 'Product not found or does not belong to the current authenticated user'
            ], 404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'image_link' => 'required',
            'price' => 'required',
            'is_published' => 'required|boolean'
        ]);

        $product = Product::find($id);
        // $product->update($request->all());
        $product->name = $request->name;
        $product->image_link = $request->image_link;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->is_published = $request->is_published;
        $product->save();
        return $product;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return Product::destroy($id);
    }

     /**
     * Search for a name
     *
     * @param  str  $name
     * @return \Illuminate\Http\Response
     */
    public function search($name)
    {
        return Product::where('name', 'like', '%'.$name.'%')->paginate(5);
    }
}

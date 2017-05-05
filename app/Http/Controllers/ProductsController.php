<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use App\Brand;
use App\State;
use App\Category;
use App\Area;
use App\Subcategory;
use App\User;
use App\Http\Requests\CreateProductRequest;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $products = Product::with('brand','subcategory','area','user');

        if (!empty($request->search_anything)) {
            # code...
            $search_anything = $request->search_anything;

            $products = $products->where(function ($query) use ($search_anything) {
                $query->orWhere('product_name', 'like', '%'.$search_anything.'%')
                      ->orWhere('product_desc', 'like', '%'.$search_anything.'%');
            });
        }

        $products = $products->paginate(5);

        $brands = Brand::pluck('brand_name', 'id');
        $categories = Category::pluck('category_name', 'id');
        $states = State::pluck('state_name', 'id');

        return view('products.index',compact('products', 'brands', 'categories', 'states'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $brands = Brand::pluck('brand_name', 'id');
        $states = State::pluck('state_name', 'id');
        $categories = Category::pluck('category_name', 'id');

        return view('products.create', compact('brands', 'states', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateProductRequest $request)
    {
        //

        $product = new Product;
        // dd($leave);
        $product->product_name = $request->input('product_name');
        $product->product_desc = $request->input('product_desc');
        $product->product_condition = $request->input('product_condition');
        $product->product_price = $request->input('product_price');
        $product->brand_id = $request->input('brand_id');
        $product->state_id = $request->input('state_id');
        $product->area_id = $request->input('area_id');
        $product->subcategory_id = $request->input('subcategory_id');
        $product->user_id = auth()->id();

        if ($request->hasFile('product_image')) {
            # code...
            $path = $request->product_image->store('images');
            $product->product_image = $request->$product_image->hashName();
        }

        $product->save();

        flash('Product successfully created')->overlay();

        return redirect() ->route('products.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $product = Product::find($id);

        $brands = Brand::pluck('brand_name', 'id');
        $states = State::pluck('state_name', 'id');
        $categories = Category::pluck('category_name', 'id');

        $areas = $this->getStateArea($product->area->state_id);
        
        $subcategories = $this->getCategoryId($product->subcategory->category_id);

        return view('products.edit', compact('brands', 'states', 'categories', 'product', 'areas', 'subcategories'));
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
        //
        $product = Product::findOrFail($id);

        $product->product_name = $request->input('product_name');
        $product->product_desc = $request->input('product_desc');
        $product->product_condition = $request->input('product_condition');
        $product->product_price = $request->input('product_price');
        $product->brand_id = $request->input('brand_id');
        $product->state_id = $request->input('state_id');
        $product->area_id = $request->input('area_id');
        $product->subcategory_id = $request->input('subcategory_id');
        $product->user_id = auth()->id();

        if ($request->hasFile('product_image')) {
            # code...
            $path = $request->product_image->store('images');
            $product->product_image = $request->$product_image->hashName();
        }

        $product->save();

        flash('Product successfully updaated')->overlay();

        return redirect() ->route('products.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    //Return area for state
    public function getStateArea($state_id){

        // echo $state_id;
        $areas = Area::whereStateId($state_id)->pluck('area_name', 'id');

        return $areas;
    }

    //Return subcategory for category
    public function getCategoryId($category_id){

        // echo $category_id;
        $category = Subcategory::whereCategoryId($category_id)->pluck('subcategory_name', 'id');

        return $category;
    }
}

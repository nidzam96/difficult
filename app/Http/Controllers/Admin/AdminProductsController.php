<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Product;
use App\Brand;
use App\State;
use App\Category;
use App\Area;
use App\Subcategory;
use App\User;
use App\Http\Requests\CreateProductRequest;
use Alert;
use App\Http\Middleware\CheckUserRole;

class AdminProductsController extends Controller
{
    public function __construct(){

        $this->middleware('auth');

        $this->middleware('check_user_role:admin');
    }
    
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

        if (!empty($request->search_state)) {
            # code...
            $search_state = $request->search_state;

            $products = $products->whereHas('area', function ($query) use ($search_state){
                $query->where('state_id', $search_state);
            });
        }

        if (!empty($request->search_category)) {
            # code...
            $search_category = $request->search_category;

            $products = $products->whereHas('subcategory', function ($query) use ($search_category){
                $query->where('category_id', $search_category);
            });
        }

        if (!empty($request->search_brand)) {
            # code...
            $search_brand = $request->search_brand;

            $prodcts = $products->whereBrandId($search_brand);
        }

        if (!empty($request->search_area)) {
            # code...
            $search_area = $request->search_area;

            $prodcts = $products->whereAreaId($search_area);
        }

        if (!empty($request->search_subcategory)) {
            # code...
            $search_subcategory = $request->search_subcategory;

            $prodcts = $products->whereSubcategoryId($search_subcategory);
        }

        $products = $products->orderBy('id', 'desc');

        $products = $products->paginate(5);

        $brands = Brand::pluck('brand_name', 'id');
        $categories = Category::pluck('category_name', 'id');
        $states = State::pluck('state_name', 'id');

        return view('admin.products.index',compact('products', 'brands', 'categories', 'states'));
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

        return view('admin.products.create', compact('brands', 'states', 'categories'));
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
            $path = $request->product_image->store('public/uploads');
            $product->product_image = $request->product_image->hashName();
        }

        $product->save();

        // flash('Product successfully created')->overlay();
        alert()->success('Product successfully created.', 'Good Work!')->autoclose(3000);

        return redirect() ->route('admin.products.index');
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

        return view('admin.products.edit', compact('brands', 'states', 'categories', 'product', 'areas', 'subcategories'));
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
            $path = $request->product_image->store('public/uploads');
            $product->product_image = $request->product_image->hashName();
        }

        $product->save();

        // flash('Product successfully updated')->overlay();
        alert()->success('Successfully updated.', 'Good Work!')->autoclose(3000);

        return redirect() ->route('admin.products.index');
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
        $product = Product::findOrFail($id);

        $product->delete();

        // flash('Product successfully deleted')->overlay();

        alert()->success('Successfully deleted.', 'Good Work!')->autoclose(3000);

        return redirect()->route('admin.products.index');
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

    //Return brands for category
    public function getCategoryBrand($category_id){

        // echo $category_id;
        $brand = Brand::whereCategoryId($category_id)->pluck('brand_name', 'id');

        return $category;
    }
}

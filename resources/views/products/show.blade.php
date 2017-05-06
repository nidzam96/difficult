@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-primary">

                <div class="panel-heading">View Product</div>

                <div class="panel-body">

                    <!-- form -->
                    {!! Form::open() !!}

                        <div class="form-group">
                            @if(!empty($product->product_image))
                                <img src="{{ asset('storage/uploads/'.$product->product_image) }}" class="img-responsive">
                            @endif
                        </div>

                        <!-- state_id select field -->
                        <div class="form-group {{ $errors->has('state_id') ? 'has-error' : false }}">
                            {!! Form::label('state_id', 'State') !!}
                            {!! Form::text('state_name', $product->area->state->state_name, ['class'=>'form-control']); !!}

                        </div>

                        <!-- area select field -->
                        <div class="form-group {{ $errors->has('area_id') ? 'has-error' : false }}">
                            {!! Form::label('area_id', 'Area') !!}
                            {!! Form::text('area_name', $product->area->area_name, ['class'=>'form-control']); !!}
                           
                        </div>

                        <!-- category select field -->
                        <div class="form-group {{ $errors->has('category_id') ? 'has-error' : false }}">
                            {!! Form::label('category_id', 'Category') !!}
                            {!! Form::text('category_name', $product->subcategory->category->category_name, ['class'=>'form-control']); !!}

                        </div>

                        <!-- subcategory select field -->
                        <div class="form-group {{ $errors->has('subcategory_id') ? 'has-error' : false }}">
                            {!! Form::label('subcategory_id', 'Subcategory') !!}
                            {!! Form::text('category_name', $product->subcategory->subcategory_name, ['class'=>'form-control']); !!}

                        </div>
                        <!-- brand_id select field -->
                        <div class="form-group {{ $errors->has('brand_id') ? 'has-error' : false }}">
                            {!! Form::label('brand_id', 'Brand') !!}
                            {!! Form::text('brand_name', $product->brand->brand_name, ['class'=>'form-control']); !!}

                        </div>

                        <!-- product__name field text -->
                        <div class="form-group {{ $errors->has('product_name') ? 'has-error' : false }}">
                            {!! Form::label('product_name', 'Product Name ') !!}
                            {!! Form::text('product_name', $product->product_name,['class'=>'form-control']); !!}
                        </div>

                        <!-- product__desc field text -->
                        <div class="form-group {{ $errors->has('product_desc') ? 'has-error' : false }}">
                            {!! Form::label('product_desc', 'Product Description') !!}
                            {!! Form::text('product_desc', $product->product_desc,['class'=>'form-control']); !!}
                        </div>

                        <!-- product__price field text -->
                        <div class="form-group {{ $errors->has('product_price') ? 'has-error' : false }}"> 
                            {!! Form::label('product_price', 'Product Price') !!}
                            {!! Form::text('product_price',$product->product_price,['class'=>'form-control']) !!}
                        </div>

                        <!-- product_condition radio button -->
                        <div class="form-group {{ $errors->has('product_condition') ? 'has-error' : false }}">
                            {!! Form::label('product_condition', 'Condition') !!} <br>
                            {!! Form::text('product_condition', $product->product_condition, ['class'=>'form-control']) !!}
                        </div>

                        <div class="form-group">
                            <a href="{{ route('products.index') }}" class="btn btn-primary btn-block">Back</a>
                        </div>

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

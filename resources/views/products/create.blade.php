@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            
            @if ($errors->all() )
                <div class="alert alert-danger" role="alert">
                    <p>Validation error.</p>
                    <ul>
                        @foreach ($errors->all() as $message)
                            <li>{{ $message }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif    
            
            <div class="panel panel-primary">

                <div class="panel-heading">Create Products</div>

                <div class="panel-body">

                    <!-- form -->
                    {!! Form::open(['route' => 'products.store']) !!}

                        <!-- product__name field text -->
                        <div class="form-group">
                            {!! Form::label('product_name', 'Product Name ') !!}
                            {!! Form::text('product_name','',['class'=>'form-control']); !!}
                        </div>

                        <!-- product__desc field text -->
                        <div class="form-group">
                            {!! Form::label('product_desc', 'Product Description') !!}
                            {!! Form::textarea('product_desc','',['class'=>'form-control']); !!}
                        </div>

                        <!-- product__price field text -->
                        <div class="form-group"> 
                            {!! Form::label('product_price', 'Product Price') !!}
                            {!! Form::text('product_price','',['class'=>'form-control']) !!}
                        </div>

                        <!-- product_condition radio button -->
                        <div class="form-group">
                            {!! Form::label('product_condition', 'Condition') !!} <br>
                            {!! Form::radio('product_condition', 'new', true, ['style'=>'margin-left: 10px']) !!} New 
                            {!! Form::radio('product_condition', 'used', false) !!} Used
                        </div>

                        <!-- branchd_id select field -->
                        <div class="form-group">
                            {!! Form::label('brand_id', 'Brand') !!}
                            {!! Form::select('brand_id', $brands, null, ['placeholder' => 'Select Brand', 'class'=>'form-control']); !!}
                        </div>

                        <!-- state_id select field -->
                        <div class="form-group">
                            {!! Form::label('state_id', 'State') !!}
                            {!! Form::select('state_id', $states, null, ['placeholder' => 'Select state', 'class'=>'form-control', 'id'=>'state_id']); !!}
                        </div>

                        <!-- area select field -->
                        <div class="form-group">
                            {!! Form::label('area_id', 'Area') !!}
                            {!! Form::select('area_id', [], null, ['placeholder' => 'Select area', 'class'=>'form-control', 'id'=>'area_id']); !!}
                        </div>

                        <!-- category select field -->
                        <div class="form-group">
                            {!! Form::label('category_id', 'Category') !!}
                            {!! Form::select('category_id', $categories, null, ['placeholder' => 'Select category', 'class'=>'form-control', 'id'=>'category_id']); !!}
                        </div>

                        <!-- subcategory select field -->
                        <div class="form-group">
                            {!! Form::label('subcategory_id', 'Subcategory') !!}
                            {!! Form::select('subcategory_id', [], null, ['placeholder' => 'Select subcategory', 'class'=>'form-control', 'id'=>'subcategory_id']); !!}
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Submit</button>

                            <a href="{{ route('products.index') }}" class="btn btn-default">Cancel</a>
                        </div>

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
    <script type="text/javascript">
        $( document ).ready(function() {
            console.log( "ASDFGHJ" );

            $("#state_id").change(function (){

                //getting state_id
                var state_id = $(this).val();
                console.log(state_id);

                //sent state_id to controller
                var ajax_url = '/products/area/' + state_id;

                $.get( ajax_url, function( data ) {
                    
                    console.log(data);

                    $('#area_id').empty().append('<option value="">Select area</option>');

                    $.each(data, function(area_id,area_name){
                        // console.log(data);
                        $('#area_id').append('<option value='+area_id+'>'+area_name+'</option>');
                    })
                });
            });

            $("#category_id").change(function (){

                //getting category_id
                var category_id = $(this).val();
                console.log(category_id);

                //sent state_id to controller
                var ajax_url = '/products/categories/' + category_id;

                $.get( ajax_url, function( data ) {
                    
                    console.log(data);

                    $('#subcategory_id').empty().append('<option value="">Select subcategory</option>');

                    $.each(data, function(id,subcategory_name){
                        // console.log(data);
                        $('#subcategory_id').append('<option value='+id+'>'+subcategory_name+'</option>');
                    })
                });
            });
        });
    </script>
@endsection

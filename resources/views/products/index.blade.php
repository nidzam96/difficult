@extends('layouts.app')

@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-md-12">

			<div class="panel panel-info">
				<div class="panel-heading">Search Products</div>

				<div class="panel-body">
					<form action="{{ route('products.index') }}" method="GET">

						<div class="row">
							<div class="col-md-3">
		                        <div class="form-group">
		                            {!! Form::label('search_state', 'State') !!}
		                            {!! Form::select('search_state', $states, Request::get('search_state'), ['placeholder' => 'Select state', 'class'=>'form-control', 'id'=>'state_id']); !!}
		                        </div>							
							</div>

							<div class="col-md-3">
		                        <div class="form-group">
		                            {!! Form::label('search_category', 'Category') !!}
		                            {!! Form::select('search_category', $categories, Request::get('search_category'), ['placeholder' => 'Select category', 'class'=>'form-control', 'id'=>'category_id']); !!}
		                        </div>	
							</div>							

							<div class="col-md-3">
		                        <div class="form-group">
		                            {!! Form::label('search_brand', 'Brand') !!}
		                            {!! Form::select('search_brand', $brands, Request::get('search_brand'), ['placeholder' => 'Select brand', 'class'=>'form-control', 'id'=>'brand_id']); !!}
		                        </div>	
							</div>

							<div class="col-md-2">
		                        <div class="form-group">
		                            {!! Form::label('serach_anything', 'By Product Name/Desc') !!}
		                            {!! Form::text('search_anything', Request::get('search_anything'),  ['class'=>'form-control']); !!}
		                        </div>								
							</div>

							<div class="col-md-1">
								<div class="form-group">
									<button type="submit" class="btn btn-danger btn-mini" style="margin-top: 27px">Search</button>
								</div>
							</div>							
						</div>

						<div class="row">
							<div class="col-md-3">
								<div class="form-group">
									{!! Form::label('search_area', 'Area') !!}
                           			{!! Form::select('search_area', [], Request::get('search_area'), ['placeholder' => 'Select area', 'class'=>'form-control', 'id'=>'area_id']); !!}
								</div>
							</div>

							<div class="col-md-3">
								<div class="form-group">
									{!! Form::label('search_subcategory', 'Subcategory') !!}
                            		{!! Form::select('search_subcategory', [], null, ['placeholder' => 'Select subcategory', 'class'=>'form-control', 'id'=>'subcategory_id']); !!}
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>

			<div class="panel panel-info">
				<div class="panel-heading">Manage Products</div>

				<div class="panel-body">

					<a href="{{ route('products.create') }}" class="btn btn-primary">Create Product</a>

					<table class="table table-bordered table-hover table-striped">
						<thead>
							<tr>
								<th>Thumbnail</th>
								<th>Product Title</th>
								<th>Product Description</th>
								<th>Price</th>
								<th>Location</th>
								<th>Condition</th>
								<th>Category</th>
								<th>Brands</th>
								<th>Seller</th>
								<th>Action</th>
								<!-- <th>Seller</th> -->
							</tr>
						</thead>
						<tbody>

							@foreach ($products as $product)
							<tr>
								<td>
									@if (!empty($product->product_image))
										<img src="{{ asset('storage/uploads/'.$product->product_image) }}" class="img-thumbnail" style="width: 200px; height:200px">	
									@else
										<img src="{{ asset('storage/uploads/default_thumbnail.png') }}" class="img-thumbnail" style="width: 200px; height: 200px">
									@endif
								</td>

								<td>{{ $product->product_name }}</td>
								<td>{{ $product->product_desc }}</td>
								<td>{{ $product->product_price }}</td>
								<td>{{ $product->area->area_name }} , {{ $product->state->state_name }}</td>
								<td>{{ $product->product_condition }}</td>
								<td>{{ $product->subcategory->subcategory_name }}</td>
								<td>{{ $product->brand->brand_name }}</td>
								<td>{{ $product->user->name }}</td>
								<td>

									<form method="POST" action="{{ route('products.destroy', $product->id) }}">
										
										<input type="hidden" name="_method" value="DELETE">

										{{ csrf_field() }}

										<a href="{{ route('products.edit', $product->id) }}" class="btn btn-warning btn-mini">Edit</a>
										<button type="button" class="btn btn-danger delete">Delete</button>

									</form>

								</td>
							</tr>
							@endforeach
						</tbody>
					</table>

					{{ $products->appends(Request::except('page'))->links() }}
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@section('script')
	<script type="text/javascript">
		
		$(document).ready(function () {

			//area catch value
            $("#state_id").change(function (){

                //getting state_id
                var state_id = $(this).val();

                getStateAreas(state_id);
            });

            var selected_state_id = '{{ Request::get('search_state') }}';

            // console.log("asdssfd ------------>>>", selected_state_id);

            if (selected_state_id.length > 0) {

                getStateAreas(selected_state_id);
            }

            function getStateAreas(state_id){

                //getting state_id
                // var state_id = $(this).val();
                // console.log(state_id);

                //sent state_id to controller
                var ajax_url = '/products/area/' + state_id;

                $.get( ajax_url, function( data ) {
                    
                    console.log(data);

                    $('#area_id').empty().append('<option value="">Select area</option>');

                    $.each(data, function(area_id,area_name){
                        // console.log(data);
                        $('#area_id').append('<option value='+area_id+'>'+area_name+'</option>');
                    })

                    var selected_area_id = '{{ Request::get('search_area') }}';

                    if(selected_area_id.length > 0){
                        
                        $('#area_id').val(selected_area_id);
                    }
                });   
            }

            //subcategory catch value
            $("#category_id").change(function (){

                //getting category_id
                var category_id = $(this).val();

                getCategorySub(category_id);
            });

            var selected_category_id = '{{ Request::get('search_category') }}';

            if (selected_category_id.length > 0) {

                getCategorySub(selected_category_id);
                // getCategoryBrand(selected_category_id);
            }

            function getCategorySub(category_id){

                //getting category_id
                // var category_id = $(this).val();
                // console.log(category_id);

                //sent state_id to controller
                var ajax_url = '/products/categories/' + category_id;

                $.get( ajax_url, function( data ) {
                    
                    console.log(data);

                    $('#subcategory_id').empty().append('<option value="">Select subcategory</option>');

                    $.each(data, function(id,subcategory_name){
                        // console.log(data);
                        $('#subcategory_id').append('<option value='+id+'>'+subcategory_name+'</option>');
                    })

                    var selected_subcategory_id = '{{ Request::get('search_subcategory') }}';

                    if(selected_subcategory_id.length > 0){
                        
                        $('#subcategory_id').val(selected_subcategory_id);
                    }
                });             
            }


            $('.delete').click(function () {

            	var closest_form = $(this).closest('form');

            	swal({
				  	title: "Are you sure?",
				  	text: "You will not be able to recover this product!",
				  	type: "warning",
				  	showCancelButton: true,
				  	confirmButtonColor: "#DD6B55",
				  	confirmButtonText: "Yes, delete it!",
				  	closeOnConfirm: false
				},
				function(){
					closest_form.submit();
				});
            })

		});
	</script>
@endsection

@extends('layouts.app')

@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-md-12">

			<div class="panel panel-info">
				<div class="panel-heading">Search Products</div>

				<div class="panel-body">
					<form action="{{ route('products.index') }}" method="GET">
						
						<div class="col-md-3">
	                        <div class="form-group">
	                            {!! Form::label('state_id', 'State') !!}
	                            {!! Form::select('state_id', $states, null, ['placeholder' => 'Select state', 'class'=>'form-control', 'id'=>'state_id']); !!}
	                        </div>							
						</div>

						<div class="col-md-3">
	                        <div class="form-group">
	                            {!! Form::label('category_id', 'Category') !!}
	                            {!! Form::select('category_id', $categories, null, ['placeholder' => 'Select category', 'class'=>'form-control', 'id'=>'category_id']); !!}
	                        </div>	
						</div>							

						<div class="col-md-3">
	                        <div class="form-group">
	                            {!! Form::label('brand_id', 'Brand') !!}
	                            {!! Form::select('brand_id', $brands, null, ['placeholder' => 'Select brand', 'class'=>'form-control', 'id'=>'brand_id']); !!}
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
								<td>{{ $product->product_name }}</td>
								<td>{{ $product->product_desc }}</td>
								<td>{{ $product->product_price }}</td>
								<td>{{ $product->area->area_name }} , {{ $product->state->state_name }}</td>
								<td>{{ $product->product_condition }}</td>
								<td>{{ $product->subcategory->subcategory_name }}</td>
								<td>{{ $product->brand->brand_name }}</td>
								<td>{{ $product->user->name }}</td>
								<td>
									<a href="{{ route('products.edit', $product->id) }}" class="btn btn-warning btn-mini">Edit</a>
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

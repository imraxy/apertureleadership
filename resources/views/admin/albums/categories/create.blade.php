@extends('admin.layouts.app')

@section('subheader__title', 'Album Categories')
	
@section('content')
		
	<!--begin::Portlet-->
	<div class="m-portlet">
		<div class="m-portlet__head">
			<div class="m-portlet__head-caption">
				<div class="m-portlet__head-title">
					<h3 class="m-portlet__head-text">
						Add New Category
					</h3>
				</div>
			</div>
		</div>

		<!--begin::Form-->
		<form method="post" class="m-form m-form--fit m-form--label-align-right" action="{{route('admin.create_albums_category')}}" enctype="multipart/form-data">
			@csrf
			<div class="m-portlet__body">
				<div class="m-form__section m-form__section--first">
					<div class="form-group m-form__group row @error('category_name') has-danger @enderror">
						<label class="col-form-label col-lg-2 col-sm-12">Category Name</label>
						<div class="col-lg-7 col-md-7 col-sm-12">
							<input type="text" class="form-control m-input" name="category_name" id="category_name" value="{{old('category_name')}}" placeholder="Category Name" autocomplete="category_name" autofocus>
							@error('category_name')
								<div class="form-control-feedback">{{ $message }}</div>
							@enderror
						</div>
					</div>					
				</div>
			</div>
			
			<div class="m-portlet__foot m-portlet__foot--fit">
				<div class="m-form__actions m-form__actions">
					<div class="row">
						<div class="col-lg-10 ml-lg-auto">
							<button type="submit" class="btn btn-success m-btn m-btn--custom">Save</button>
							<a href="{{route('admin.albums_categories')}}" class="btn btn-secondary m-btn m-btn--custom">Cancel</a>
						</div>
					</div>
				</div>
			</div>
		</form>

		<!--end::Form-->
	</div>
	
@endsection
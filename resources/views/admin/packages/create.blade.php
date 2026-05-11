@extends('admin.layouts.app')

@section('subheader__title', 'Manage Packages')
	
@section('content')
		
	<!--begin::Portlet-->
	<div class="m-portlet">
		<div class="m-portlet__head">
			<div class="m-portlet__head-caption">
				<div class="m-portlet__head-title">
					<h3 class="m-portlet__head-text">
						Add Package
					</h3>
				</div>
			</div>
		</div>

		<!--begin::Form-->
		<!--form class="m-form m-form--fit m-form--label-align-right"-->
		<form method="post" class="m-form m-form--fit m-form--label-align-right" action="{{route('admin.create_packages')}}">
			@csrf
			<div class="m-portlet__body">
				
				<div class="m-form__section m-form__section--first">
					
					<div class="form-group m-form__group row @error('title') has-danger @enderror">
						<label class="col-form-label col-lg-2 col-sm-12">Title</label>
						<div class="col-lg-7 col-md-7 col-sm-12">
							<input type="text" class="form-control m-input" name="title" id="title" value="{{old('title')}}" placeholder="Title" autocomplete="title" autofocus>
							@error('title')
								<div class="form-control-feedback">{{ $message }}</div>
							@enderror
						</div>
					</div>
					
					<div class="form-group m-form__group row @error('price') has-danger @enderror">
						<label class="col-form-label col-lg-2 col-sm-12">Price</label>
						<div class="col-lg-7 col-md-7 col-sm-12">
							<input type="text" class="form-control m-input" name="price" onkeypress="return NumericValidation(event);" id="price" value="{{old('price')}}" placeholder="Price" autocomplete="price">
							@error('price')
								<div class="form-control-feedback">{{ $message }}</div>
							@enderror
						</div>
					</div>
					
					<div class="form-group m-form__group row @error('currency') has-danger @enderror">
						<label class="col-form-label col-lg-2 col-sm-12">Currency</label>
						<div class="col-lg-7 col-md-7 col-sm-12">
							<input type="text" class="form-control m-input" name="currency" id="currency" value="{{old('currency')}}" placeholder="Currency" autocomplete="currency">
							@error('currency')
								<div class="form-control-feedback">{{ $message }}</div>
							@enderror
						</div>
					</div>
					
					<div class="form-group m-form__group row @error('discount') has-danger @enderror">
						<label class="col-form-label col-lg-2 col-sm-12">Discount</label>
						<div class="col-lg-7 col-md-7 col-sm-12">
							<input type="text" class="form-control m-input" name="discount" onkeypress="return NumericValidation(event);" id="discount" value="{{old('discount')}}" placeholder="Discount" autocomplete="discount">
							@error('discount')
								<div class="form-control-feedback">{{ $message }}</div>
							@enderror
						</div>
					</div>
					
					<div class="form-group m-form__group row @error('details') has-danger @enderror">
						<label class="col-form-label col-lg-2 col-sm-12">Details</label>
						<div class="col-lg-7 col-md-7 col-sm-12">
							<textarea class="form-control" name="details" placeholder="Details" rows="5">{{ old('details') }}</textarea>
							@error('details')
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
							<a href="{{route('admin.packages')}}" class="btn btn-secondary m-btn m-btn--custom">Cancel</a>
						</div>
					</div>
				</div>
			</div>
		</form>

		<!--end::Form-->
	</div>
	
@endsection

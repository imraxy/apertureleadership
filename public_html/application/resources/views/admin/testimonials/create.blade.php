@extends('admin.layouts.app')

@section('subheader__title', 'Testimonials')
	
@section('content')
		
	<!--begin::Portlet-->
	<div class="m-portlet">
		<div class="m-portlet__head">
			<div class="m-portlet__head-caption">
				<div class="m-portlet__head-title">
					<h3 class="m-portlet__head-text">
						Add Testimonial
					</h3>
				</div>
			</div>
		</div>

		<!--begin::Form-->
		<form method="post" class="m-form m-form--fit m-form--label-align-right" action="{{route('admin.create_testimonial')}}" enctype="multipart/form-data">
			@csrf
			<div class="m-portlet__body">
				<div class="m-form__section m-form__section--first">
					<div class="form-group m-form__group row @error('name') has-danger @enderror">
						<label class="col-form-label col-lg-2 col-sm-12">Name</label>
						<div class="col-lg-7 col-md-7 col-sm-12">
							<input type="text" class="form-control m-input" name="name" id="name" value="{{old('name')}}" placeholder="Name" autocomplete="name" autofocus>
							@error('name')
								<div class="form-control-feedback">{{ $message }}</div>
							@enderror
						</div>
					</div>
					
					<div class="form-group m-form__group row @error('message') has-danger @enderror">
						<label class="col-form-label col-lg-2 col-sm-12">Message</label>
						<div class="col-lg-7 col-md-7 col-sm-12">
							<textarea class="form-control" name="message" placeholder="Message" rows="5">{{ old('message') }}</textarea>
							@error('message')
							<div class="form-control-feedback">{{ $message }}</div>
							@enderror
						</div>
					</div>
					
					<div class="form-group m-form__group row @error('image') has-danger @enderror">
						<label class="col-form-label col-lg-2 col-sm-12">Image</label>
						<div class="col-lg-7 col-md-7 col-sm-12">
							<input type="file" class="form-control m-input" name="image" accept="image/*" onchange="loadFile(event,'image')" />
							<span class="m-form__help">Allowed formats - jpg, jpeg, png.</span>
							@error('image')
								<div class="form-control-feedback">{{ $message }}</div>
							@enderror
							<p></p>
							<div class="fileinput fileinput-exists">
								<div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;">								
									<img src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=no+image" id="image" style="max-height: 140px;">
								</div>
							</div>
						</div>
					</div>
					
				</div>
			</div>
			
			<div class="m-portlet__foot m-portlet__foot--fit">
				<div class="m-form__actions m-form__actions">
					<div class="row">
						<div class="col-lg-10 ml-lg-auto">
							<button type="submit" class="btn btn-success m-btn m-btn--custom">Save</button>
							<a href="{{route('admin.testimonials')}}" class="btn btn-secondary m-btn m-btn--custom">Cancel</a>
						</div>
					</div>
				</div>
			</div>
		</form>

		<!--end::Form-->
	</div>
	
@endsection
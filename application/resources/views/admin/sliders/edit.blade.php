@extends('admin.layouts.app')

@section('subheader__title', 'Sliders')
	
@section('content')
		
	<!--begin::Portlet-->
	<div class="m-portlet">
		<div class="m-portlet__head">
			<div class="m-portlet__head-caption">
				<div class="m-portlet__head-title">
					<h3 class="m-portlet__head-text">
						Edit Slider
					</h3>
				</div>
			</div>
		</div>

		<!--begin::Form-->
		<!--form class="m-form m-form--fit m-form--label-align-right"-->
		<form method="post" class="m-form m-form--fit m-form--label-align-right" action="{{route('admin.edit_slider', $slider->id)}}" enctype="multipart/form-data">
			@csrf
			@method('PUT')
			<div class="m-portlet__body">
				<div class="m-form__section m-form__section--first">
					<div class="form-group m-form__group row @error('title') has-danger @enderror">
						<label class="col-form-label col-lg-2 col-sm-12">Title</label>
						<div class="col-lg-7 col-md-7 col-sm-12">
							<input type="text" class="form-control m-input" name="title" id="title" value="{{old('title', $slider->title ?? '')}}" placeholder="Title" autocomplete="title" autofocus>
							@error('title')
								<div class="form-control-feedback">{{ $message }}</div>
							@enderror
						</div>
					</div>					
					<div class="form-group m-form__group row @error('slider_image') has-danger @enderror">
						<label class="col-form-label col-lg-2 col-sm-12">Image</label>
						<div class="col-lg-7 col-md-7 col-sm-12">
							<input type="file" class="form-control m-input" name="slider_image" onchange="loadFile(event,'slider_image')" />
							@error('slider_image')
								<div class="form-control-feedback">{{ $message }}</div>
							@enderror
							<p></p>
							<div class="fileinput fileinput-exists">
								<div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;">								
									@if(!empty($slider->image) && File::exists('application/public/uploads/sliders/'.$slider->image))	
										<img src="{{ asset('application/public/uploads/sliders/'.$slider->image) }}" id="slider_image" style="max-height: 140px;">
									@else
										<img src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=no+image" id="slider_image" style="max-height: 140px;">
									@endif
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
							<button type="submit" class="btn btn-success m-btn m-btn--custom">Update</button>
							<a href="{{route('admin.sliders')}}" class="btn btn-secondary m-btn m-btn--custom">Cancel</a>
						</div>
					</div>
				</div>
			</div>
		</form>

		<!--end::Form-->
	</div>
	
@endsection
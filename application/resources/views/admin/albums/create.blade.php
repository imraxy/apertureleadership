@extends('admin.layouts.app')

@section('subheader__title', 'Albums')

@section('breadcrumbs') 
	<li class="m-nav__separator">-</li>
	<li class="m-nav__item">
		<a href="" class="m-nav__link">
			<span class="m-nav__link-text">Add Album</span>
		</a>
	</li>
	
@endsection
	
@section('content')
		
	<!--begin::Portlet-->
	<div class="m-portlet">
		<div class="m-portlet__head">
			<div class="m-portlet__head-caption">
				<div class="m-portlet__head-title">
					<h3 class="m-portlet__head-text">
						Add Album
					</h3>
				</div>
			</div>
		</div>

		<!--begin::Form-->
		<form method="post" class="m-form m-form--fit m-form--label-align-right" action="{{route('admin.add_albums_session_image')}}" enctype="multipart/form-data">
			@csrf
			<div class="m-portlet__body">
				
				<div class="m-form__section m-form__section--first">
					
					<div class="form-group m-form__group row @error('category') has-danger @enderror">
						<label class="col-xl-2 col-lg-2 col-form-label">Category</label>
						<div class="col-xl-9 col-lg-9">
							<select class="form-control m-input" name="category" required="required">
								<option value="">Select Category</option>
								@foreach($categories as $category)
								<option value="{{$category->id}}" {{ old('category') == $category->id ? 'selected' : '' }}>{{$category->name}}</option>
								@endforeach
							</select>
							@error('category')
							<div class="form-control-feedback">{{ $message }}</div>
							@enderror
						</div>
					</div>
					
					<div class="form-group m-form__group row @error('title') has-danger @enderror">
						<label class="col-form-label col-lg-2 col-sm-12">Title</label>
						<div class="col-lg-7 col-md-7 col-sm-12">
							<input type="text" class="form-control m-input" name="title" id="title" value="{{old('title')}}" placeholder="Title" autocomplete="title" required="required" autofocus>
							@error('title')
								<div class="form-control-feedback">{{ $message }}</div>
							@enderror
						</div>
					</div>

					<div class="form-group m-form__group row @error('description') has-danger @enderror">
						<label class="col-form-label col-lg-2 col-sm-12">Description</label>
						<div class="col-lg-7 col-md-7 col-sm-12">
							<textarea class="form-control" name="description" id="description" placeholder="Description" rows="5">{{old('description')}}</textarea>
							@error('description')
								<div class="form-control-feedback">{{ $message }}</div>
							@enderror
						</div>
					</div>
					
					<div class="form-group m-form__group row @error('session_image') has-danger @enderror">
						<label class="col-form-label col-lg-2 col-sm-12">Gallery Session Image</label>
						<div class="col-lg-7 col-md-7 col-sm-12">
							<input type="file" class="form-control m-input" name="session_image" required="required" accept="image/*" onchange="loadFile(event,'featured_image')" />
							<span class="m-form__help">Allowed formats - jpg, jpeg, png.</span>
							@error('session_image')
								<div class="form-control-feedback">{{ $message }}</div>
							@enderror
							<p></p>
							<div class="fileinput fileinput-exists">
								<div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;">	
									<img src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=no+image" id="featured_image" style="max-height: 140px;">
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
							<a href="{{route('admin.albums')}}" class="btn btn-secondary m-btn m-btn--custom">Cancel</a>
						</div>
					</div>
				</div>
			</div>
		</form>

		<!--end::Form-->
	</div>
	
@endsection
@push('js')
<script src="{{ asset('content/assets/demo/default/custom/crud/forms/widgets/select2.js') }}" type="text/javascript"></script>
<script src="{{ asset('content/assets/ckeditor/ckeditor.js')}}"></script>
<script data-sample="1">
	CKEDITOR.replace('description');
</script>
@endpush
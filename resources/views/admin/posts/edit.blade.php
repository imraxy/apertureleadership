@extends('admin.layouts.app')

@section('subheader__title', 'Posts')
	
@section('content')
	
	<div class="progressBar-loading-new" style="display:none;">
		<div class="loaderific" data-ui-test="loading"><div class="loaderific-spinner"></div></div>
	</div>
		
	<!--begin::Portlet-->
	<div class="m-portlet">
		<div class="m-portlet__head">
			<div class="m-portlet__head-caption">
				<div class="m-portlet__head-title">
					<h3 class="m-portlet__head-text">
						Edit Post Detail
					</h3>
				</div>
			</div>
		</div>

		<!--begin::Form-->
		<!--form class="m-form m-form--fit m-form--label-align-right"-->
		<form method="post" class="m-form m-form--fit m-form--label-align-right" action="{{route('admin.edit_posts', $post->id)}}" enctype="multipart/form-data">
			@method('PUT')
									
			@csrf
			<div class="m-portlet__body">
				
				<div class="m-form__section m-form__section--first">
					
					<div class="form-group m-form__group row @error('category') has-danger @enderror">
						<label class="col-xl-2 col-lg-2 col-form-label">Category</label>
						<div class="col-xl-9 col-lg-9">
							<select class="form-control m-input" name="category">
								<option value="">Select Category</option>
								@foreach($categories as $category)
								<option value="{{$category->id}}" {{ old('category', $post->blog_category_id) == $category->id ? 'selected' : '' }}>{{$category->name}}</option>
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
							<input type="text" class="form-control m-input" name="title" id="title" value="{{old('title', $post->title ?? '')}}" placeholder="Title" autocomplete="title" autofocus>
							@error('title')
								<div class="form-control-feedback">{{ $message }}</div>
							@enderror
						</div>
					</div>
					{{--
					<div class="form-group m-form__group row @error('permalink') has-danger @enderror">
						<label class="col-form-label col-lg-2 col-sm-12">Permalink</label>
						<div class="col-lg-7 col-md-7 col-sm-12">
							<input type="text" class="form-control m-input" name="permalink" id="permalink" value="{{old('permalink', $post->permalink ?? '')}}" placeholder="Permalink" autocomplete="permalink">
							@error('permalink')
								<div class="form-control-feedback">{{ $message }}</div>
							@enderror
						</div>
					</div>
					--}}
					<div class="form-group m-form__group row @error('description') has-danger @enderror">
						<label class="col-form-label col-lg-2 col-sm-12">Description</label>
						<div class="col-lg-7 col-md-7 col-sm-12">
							<textarea class="form-control" name="description" id="description" placeholder="Description" rows="10">{{old('description', $post->description ?? '')}}</textarea>
							@error('description')
								<div class="form-control-feedback">{{ $message }}</div>
							@enderror
						</div>
					</div>
					
					<div class="form-group m-form__group row @error('meta_keywords') has-danger @enderror">
						<label class="col-form-label col-lg-2 col-sm-12">Meta Keywords</label>
						<div class="col-lg-7 col-md-7 col-sm-12">
							<textarea class="form-control" name="meta_keywords" placeholder="Meta Keywords" rows="5">{{ old('meta_keywords', $post->meta_keywords ?? '') }}</textarea>
							@error('meta_keywords')
							<div class="form-control-feedback">{{ $message }}</div>
							@enderror
						</div>
					</div>
					
					<div class="form-group m-form__group row @error('meta_description') has-danger @enderror">
						<label class="col-form-label col-lg-2 col-sm-12">Meta Description</label>
						<div class="col-lg-7 col-md-7 col-sm-12">
							<textarea class="form-control" name="meta_description" placeholder="Meta Keywords" rows="5">{{ old('meta_description', $post->meta_description ?? '') }}</textarea>
							@error('meta_description')
							<div class="form-control-feedback">{{ $message }}</div>
							@enderror
						</div>
					</div>
					
					<div class="form-group m-form__group row @error('featured_image') has-danger @enderror">
						<label class="col-form-label col-lg-2 col-sm-12">Featured Image</label>
						<div class="col-lg-7 col-md-7 col-sm-12">
							<input type="file" class="form-control m-input" name="featured_image" accept="image/*" onchange="loadFile(event,'featured_image')" />
							<span class="m-form__help">Allowed formats - jpg, jpeg, png.</span>
							@error('featured_image')
								<div class="form-control-feedback">{{ $message }}</div>
							@enderror
							<p></p>
							<div class="fileinput fileinput-exists">
								<div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;">
									@if(!empty($post->featured_image) && File::exists('application/public/uploads/journals/'.$post->featured_image))
										<img src="{{asset('application/public/uploads/journals/'.$post->featured_image)}}" id="featured_image" style="max-height: 140px;">
									@else
										<img src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=no+image" id="featured_image" style="max-height: 140px;">
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
							<a href="{{route('admin.posts')}}" class="btn btn-secondary m-btn m-btn--custom">Cancel</a>
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
	CKEDITOR.replace( 'description' );
</script>
@endpush
@extends('admin.layouts.app')

@section('subheader__title', 'About Me')

@section('content')
			
	
		
	<div class="row">
	
		<div class="col-lg-12">

			<!--begin::Portlet-->
			<div class="m-portlet">
				<div class="m-portlet__head">
					<div class="m-portlet__head-caption">
						<div class="m-portlet__head-title">
							<span class="m-portlet__head-icon m--hide">
								<i class="la la-gear"></i>
							</span>
							<h3 class="m-portlet__head-text">
								About Me
							</h3>
						</div>
					</div>
					
				</div>

				<!--begin::Form-->
				<form method="post" class="m-form m-form--label-align-right" action="{{route('admin.about_us')}}" enctype="multipart/form-data">
					@csrf
					<div class="m-portlet__body">
						
						<div class="m-form__section m-form__section--first">
							
							<div class="form-group m-form__group row @error('name') has-danger @enderror">
								<label class="col-lg-2 col-form-label">Name</label>
								<div class="col-lg-6">
									<input type="text" class="form-control m-input" value="{{ old('name', $about_me->name ?? '') }}" name="name" placeholder="Name" autocomplete="name" autofocus>
									@error('name')
									<div class="form-control-feedback">{{ $message }}</div>
									@enderror
								</div>
							</div>
							
							<div class="form-group m-form__group row @error('about_me_content') has-danger @enderror">
								<label class="col-lg-2 col-form-label">About Me</label>
								<div class="col-lg-6">
									<textarea cols="110" id="editor1" name="about_me_content" rows="10">{{old('about_me_content', $about_me->content ?? '')}}</textarea>
									
									@error('about_me_content')
									<div class="form-control-feedback">{{ $message }}</div>
									@enderror
								</div>
							</div>
							
							<div class="form-group m-form__group row @error('about_me_image') has-danger @enderror">
								<label class="col-lg-2 col-form-label">Image</label>
								<div class="col-lg-6">
									<input type="file" class="form-control m-input" accept="image/*" id="about_me_image" name="about_me_image" onchange="loadFile(event,'aboutImg')" />
									@error('about_me_image')
									<div class="form-control-feedback">{{ $message }}</div>
									@enderror
									<p></p>
									<div class="fileinput fileinput-exists">
										<div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;">
											@if(isset($about_me->image) && File::exists('application/public/uploads/about/'.$about_me->image))
												<img src="{{asset('application/public/uploads/about/'.$about_me->image)}}" id="aboutImg" style="max-height: 140px;">
											@else
												<img src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=no+image" id="aboutImg" style="max-height: 140px;">
											@endif
										</div>
									</div>
								</div>
							</div>
							
							<div class="form-group m-form__group row @error('satisfied_client') has-danger @enderror">
								<label class="col-lg-2 col-form-label">Satisfied Clients</label>
								<div class="col-lg-6">
									<input type="text" class="form-control" name="satisfied_client" onkeypress="return NumericValidation(event);" value="{{old('satisfied_client', $about_me->no_of_clients ?? '')}}" placeholder="No of Satisfied Clients" autocomplete="satisfied_client" >
									@error('satisfied_client')
									<div class="form-control-feedback">{{ $message }}</div>
									@enderror
								</div>
							</div>
							
							<div class="form-group m-form__group row @error('meetings') has-danger @enderror">
								<label class="col-lg-2 col-form-label">Meetings</label>
								<div class="col-lg-6">
									<input type="text" class="form-control" name="meetings" onkeypress="return NumericValidation(event);" value="{{old('meetings', $about_me->no_of_meetings ?? '')}}" placeholder="No of Meetings" autocomplete="meetings" >
									@error('meetings')
									<div class="form-control-feedback">{{ $message }}</div>
									@enderror
								</div>
							</div>
							
							<div class="form-group m-form__group row @error('sessions_have_done') has-danger @enderror">
								<label class="col-lg-2 col-form-label">Sessions have done</label>
								<div class="col-lg-6">
									<input type="text" class="form-control" name="sessions_have_done" onkeypress="return NumericValidation(event);" value="{{old('sessions_have_done', $about_me->no_of_sessions ?? '')}}" placeholder="No of Sessions have done" autocomplete="sessions_have_done" >
									@error('sessions_have_done')
									<div class="form-control-feedback">{{ $message }}</div>
									@enderror
								</div>
							</div>
							
							<div class="form-group m-form__group row @error('established_from') has-danger @enderror">
								<label class="col-lg-2 col-form-label">Established from</label>
								<div class="col-lg-6">
									<input type="text" class="form-control" name="established_from" onkeypress="return NumericValidation(event);" value="{{old('established_from', $about_me->established_year ?? '')}}" placeholder="No of Sessions have done" autocomplete="established_from" >
									@error('established_from')
									<div class="form-control-feedback">{{ $message }}</div>
									@enderror
								</div>
							</div>

						</div>
					</div>
					<div class="m-portlet__foot m-portlet__foot--fit">
						<div class="m-form__actions m-form__actions">
							<div class="row">
								<div class="col-lg-2"></div>
								<div class="col-lg-6">
									<button type="submit" class="btn btn-success m-btn m-btn--custom">Update</button>
									<a href="{{route('admin_dashboard')}}" class="btn btn-secondary m-btn m-btn--custom">Cancel</a>
								</div>
							</div>
						</div>
					</div>
				</form>
				<!--end::Form-->
			</div>
			<!--end::Portlet-->
		</div>
	</div>

@endsection

@push('js')
    <script>
        loadFile = function(event, id) {
            var output = document.getElementById(id);
            output.src = URL.createObjectURL(event.target.files[0]);
        };
    </script>
	
	<script src="{{ asset('content/assets/ckeditor/ckeditor.js')}}"></script>
	<script data-sample="1">
		CKEDITOR.replace( 'editor1' );
	</script>
@endpush

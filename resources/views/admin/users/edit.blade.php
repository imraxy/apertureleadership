@extends('admin.layouts.app')

@section('subheader__title', 'Manage Users')
	
@section('content')
		
	<!--begin::Portlet-->
	<div class="m-portlet">
		<div class="m-portlet__head">
			<div class="m-portlet__head-caption">
				<div class="m-portlet__head-title">
					<h3 class="m-portlet__head-text">
						Edit User
					</h3>
				</div>
			</div>
		</div>

		<!--begin::Form-->
		<!--form class="m-form m-form--fit m-form--label-align-right"-->
		<form method="post" class="m-form m-form--fit m-form--label-align-right" action="{{route('admin.edit_user', $user->id)}}" enctype="multipart/form-data">
			@csrf
			@method('PUT')
			<div class="m-portlet__body">
				<div class="m-form__section m-form__section--first">
					<div class="form-group m-form__group row @error('user_name') has-danger @enderror">
						<label class="col-form-label col-lg-2 col-sm-12">User Name</label>
						<div class="col-lg-7 col-md-7 col-sm-12">
							<input type="text" class="form-control m-input" name="user_name" id="user_name" value="{{old('user_name', $user->user_name ?? '')}}" placeholder="User Name" autocomplete="user_name" autofocus>
							@error('user_name')
								<div class="form-control-feedback">{{ $message }}</div>
							@enderror
						</div>
					</div>
					<div class="form-group m-form__group row @error('email') has-danger @enderror">
						<label class="col-form-label col-lg-2 col-sm-12">Email</label>
						<div class="col-lg-7 col-md-7 col-sm-12">
							<input type="email" class="form-control m-input" id="email" name="email" value="{{old('email', $user->email ?? '')}}" placeholder="Email" autocomplete="email" >
							@error('email')
								<div class="form-control-feedback">{{ $message }}</div>
							@enderror
						</div>
					</div>
					<div class="form-group m-form__group row @error('user_image') has-danger @enderror">
						<label class="col-form-label col-lg-2 col-sm-12">Image</label>
						<div class="col-lg-7 col-md-7 col-sm-12">
							<input type="file" class="form-control m-input" id="user_image" name="user_image" onchange="loadFile(event,'avatar')" />
							@error('user_image')
								<div class="form-control-feedback">{{ $message }}</div>
							@enderror
							<p></p>
							<div class="fileinput fileinput-exists">
								<div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;">
									@if(!empty($user->avatar) && File::exists('application/public/uploads/users/'.$user->avatar))	
										<img src="{{ asset('application/public/uploads/users/'.$user->avatar) }}" id="avatar" style="max-height: 140px;">
									@else
										<img src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=no+image" id="avatar" style="max-height: 140px;">
									@endif
								</div>
							</div>
						</div>
					</div>
					<div class="form-group m-form__group row @error('password') has-danger @enderror">
						<label class="col-form-label col-lg-2 col-sm-12">Password</label>
						<div class="col-lg-7 col-md-7 col-sm-12">
							<input type="password" class="form-control m-input" id="password" name="password" placeholder="Password" autocomplete="password" >
							@error('password')
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
							<button type="submit" class="btn btn-success m-btn m-btn--custom">Update</button>
							<a href="{{route('admin.users')}}" class="btn btn-secondary m-btn m-btn--custom">Cancel</a>
						</div>
					</div>
				</div>
			</div>
		</form>

		<!--end::Form-->
	</div>
	
@endsection
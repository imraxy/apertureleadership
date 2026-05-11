@extends('admin.layouts.app')

@section('subheader__title', 'My Profile')
	
@section('content')
	@php $admin_detail = Auth::guard('admin')->user(); @endphp
	<div class="row">
		<div class="col-lg-4">
			<div class="m-portlet m-portlet--full-height  ">
				<div class="m-portlet__body">
					<div class="m-card-profile">
						<div class="m-card-profile__title m--hide">
							Your Profile
						</div>
						@if($admin_detail->avatar && File::exists('application/public/uploads/users/'.$admin_detail->avatar))
						<div class="m-card-profile__pic">
							<div class="m-card-profile__pic-wrapper">
								<img src="{{asset('application/public/uploads/users/'.$admin_detail->avatar)}}" alt="{{ $admin_detail->name ? : ''  }}"  />
							</div>
						</div>
						@endif
						<div class="m-card-profile__details">
							<span class="m-card-profile__name">{{ $admin_detail->name }}</span>
							<a href="" class="m-card-profile__email m-link">{{ $admin_detail->email }}</a>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="col-lg-8">
			
			<div class="m-portlet m-portlet--full-height m-portlet--tabs  ">
				<div class="m-portlet__head">
					<div class="m-portlet__head-tools">
						<ul class="nav nav-tabs m-tabs m-tabs-line m-tabs-line--left m-tabs-line--success" role="tablist">
							<li class="nav-item m-tabs__item">
								<a class="nav-link m-tabs__link active" data-toggle="tab" href="#m_user_profile_tab_1" role="tab">
									<i class="flaticon-share m--hide"></i>
									Update Profile
								</a>
							</li>
						</ul>
					</div>
				</div>

				<div class="tab-content">
				
					<div class="tab-pane active" id="m_user_profile_tab_1">
						
						<form class="m-form m-form--fit m-form--label-align-right" action="{{ route('admin_profile') }}" method="post" enctype="multipart/form-data">
							
							@method('PUT')
							
							@csrf
							
							<div class="m-portlet__body">
								
								<div class="m-form__section m-form__section--first">
									
									<div class="form-group m-form__group row @error('name') has-danger @enderror">
										<label for="example-text-input" class="col-2 col-form-label">Name</label>
										<div class="col-7">
											<input class="form-control m-input" type="text" value="{{ old('name', $admin_detail->name) }}" name="name" autocomplete="name" autofocus="">
											@error('name')
											<div class="form-control-feedback">{{ $message }}</div>
											@enderror
										</div>
									</div>

									<div class="form-group m-form__group row @error('email') has-danger @enderror">
										<label for="example-text-input" class="col-2 col-form-label">Email</label>
										<div class="col-7">
											<input class="form-control m-input" type="email" value="{{ old('email', $admin_detail->email) }}" name="email" autocomplete="email">
											@error('email')
											<div class="form-control-feedback">{{ $message }}</div>
											@enderror
										</div>
									</div>
									
									<div class="form-group m-form__group row @error('password') has-danger @enderror">
										<label for="password" class="col-2 col-form-label">New Password</label>
										<div class="col-7">
											<input class="form-control m-input" type="password" name="password" id="password" autocomplete="password" placeholder="New Password" >
											@error('password')
											<div class="form-control-feedback">{{ $message }}</div>
											@enderror
										</div>
									</div>

									<div class="form-group m-form__group row @error('password_confirmation') has-danger @enderror">
										<label for="password-confirm" class="col-2 col-form-label">Confirm Password</label>
										<div class="col-7">
											<input class="form-control m-input" type="password" id="password-confirm" name="password_confirmation" autocomplete="password_confirmation" placeholder="Confirm Password">
											@error('password_confirmation')
											<div class="form-control-feedback">{{ $message }}</div>
											@enderror
										</div>
									</div>
									
									<div class="form-group m-form__group row @error('profile_image') has-danger @enderror">
										<label for="password-confirm" class="col-2 col-form-label">Profile Image</label>
										<div class="col-7">
											<input class="form-control m-input" type="file" name="profile_image">
											@error('profile_image')
											<div class="form-control-feedback">{{ $message }}</div>
											@enderror
										</div>
									</div>
								
								</div>
							</div>

							<div class="m-portlet__foot m-portlet__foot--fit">
								<div class="m-form__actions">
									<div class="row">
										<div class="col-lg-10 ml-lg-auto">
											<button type="submit" class="btn btn-success m-btn m-btn--custom">Update</button>
											<a href="{{route('admin_dashboard')}}" class="btn btn-secondary m-btn m-btn--custom">Cancel</a>
										</div>
									</div>
								</div>
							</div>

						</form>
					</div>
				</div>
			</div>
		</div>
	</div>

@endsection
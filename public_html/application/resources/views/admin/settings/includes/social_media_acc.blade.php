							<form class="m-form m-form--label-align-right" action="{{ route('admin.settings.update') }}"  method="POST" enctype="multipart/form-data">
								
								@method('PUT')
									
								@csrf
								
								<input type="hidden" name="current_request" value="4" />
								
								<div class="m-portlet__body">
								
									<div class="m-form__section m-form__section--first">
									
										<div class="form-group m-form__group row @error('facebook') has-danger @enderror">
											<label class="col-lg-2 col-form-label">Facebook Account</label>
											<div class="col-lg-6">
												<input type="url" class="form-control m-input" name="facebook" value="{{ old('facebook', config('settings.facebook')) }}" placeholder="Facebook Account" autocomplete="facebook">
												@error('facebook')
												<div class="form-control-feedback">{{ $message }}</div>
												@enderror
											</div>
										</div>
										
										<div class="form-group m-form__group row @error('twitter') has-danger @enderror">
											<label class="col-lg-2 col-form-label">Twitter Account</label>
											<div class="col-lg-6">
												<input type="url" class="form-control m-input" name="twitter" value="{{ old('twitter', config('settings.twitter')) }}" placeholder="Twitter Account" autocomplete="twitter">
												@error('twitter')
												<div class="form-control-feedback">{{ $message }}</div>
												@enderror
											</div>
										</div>
										
										<div class="form-group m-form__group row @error('instagram') has-danger @enderror">
											<label class="col-lg-2 col-form-label">Instagram Account</label>
											<div class="col-lg-6">
												<input type="url" class="form-control m-input" name="instagram" value="{{ old('instagram', config('settings.instagram')) }}" placeholder="Instagram Account">
												@error('instagram')
												<div class="form-control-feedback">{{ $message }}</div>
												@enderror
											</div>
										</div>
										
										<div class="form-group m-form__group row @error('youtube') has-danger @enderror">
											<label class="col-lg-2 col-form-label">Youtube Account</label>
											<div class="col-lg-6">
												<input type="url" class="form-control m-input" name="youtube" value="{{ old('youtube', config('settings.youtube')) }}" placeholder="Youtube Account">
												@error('youtube')
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
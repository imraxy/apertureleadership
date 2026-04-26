							<form class="m-form m-form--label-align-right " action="{{ route('admin.settings.update') }}"  method="POST" enctype="multipart/form-data">
								
								@method('PUT')
									
								@csrf
								
								<input type="hidden" name="current_request" value="3" />
								
								<div class="m-portlet__body">
								
									<div class="m-form__section m-form__section--first">
									
										<div class="form-group m-form__group row @error('webmaster_email') has-danger @enderror">
											<label class="col-lg-2 col-form-label">Webmaster Email</label>
											<div class="col-lg-6">
												<input type="email" class="form-control m-input" name="webmaster_email" value="{{ old('webmaster_email', config('settings.webmaster_email')) }}" placeholder="Webmaster Email" autocomplete="webmaster_email">
												@error('webmaster_email')
												<div class="form-control-feedback">{{ $message }}</div>
												@enderror
											</div>
										</div>
										
										<div class="form-group m-form__group row @error('address') has-danger @enderror">
											<label class="col-lg-2 col-form-label">Address</label>
											<div class="col-lg-6">
												<textarea class="form-control" name="address" placeholder="Address" rows="4">{{ old('address', config('settings.address')) }}</textarea>
												@error('address')
												<div class="form-control-feedback">{{ $message }}</div>
												@enderror
											</div>
										</div>
										
										<div class="form-group m-form__group row @error('phone') has-danger @enderror">
											<label class="col-lg-2 col-form-label">Phone</label>
											<div class="col-lg-6">
												<input type="text" class="form-control m-input" name="phone" onkeypress="return NumericValidation(event);" value="{{ old('phone', config('settings.phone')) }}" placeholder="Phone">
												@error('phone')
												<div class="form-control-feedback">{{ $message }}</div>
												@enderror
											</div>
										</div>
										
										<div class="form-group m-form__group row @error('whatsapp') has-danger @enderror">
											<label class="col-lg-2 col-form-label">Whatsapp</label>
											<div class="col-lg-6">
												<input type="text" class="form-control m-input" name="whatsapp" onkeypress="return NumericValidation(event);" value="{{ old('whatsapp', config('settings.whatsapp')) }}" placeholder="Whatsapp">
												@error('whatsapp')
												<div class="form-control-feedback">{{ $message }}</div>
												@enderror
											</div>
										</div>
										
										<div class="form-group m-form__group row @error('skype') has-danger @enderror">
											<label class="col-lg-2 col-form-label">Skype</label>
											<div class="col-lg-6">
												<input type="text" class="form-control m-input" name="skype" value="{{ old('skype', config('settings.skype')) }}" placeholder="Skype">
												@error('skype')
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
							<form class="m-form m-form--label-align-right " action="{{ route('admin.settings.update') }}"  method="POST" enctype="multipart/form-data">
								
								@method('PUT')
									
								@csrf
								
								<input type="hidden" name="current_request" value="1" />
								
								<div class="m-portlet__body">
								
									<div class="m-form__section m-form__section--first">
									
										<div class="form-group m-form__group row @error('site_title') has-danger @enderror">
											<label class="col-lg-2 col-form-label">Site Title</label>
											<div class="col-lg-6">
												<input type="text" class="form-control m-input" name="site_title" placeholder="Site Title" value="{{ old('site_title', config('settings.site_title')) }}" autocomplete="site_title" autofocus="">
												@error('site_title')
												<div class="form-control-feedback">{{ $message }}</div>
												@enderror
											</div>
										</div>
										
										<div class="form-group m-form__group row @error('copyright') has-danger @enderror">
											<label class="col-lg-2 col-form-label">Copyright</label>
											<div class="col-lg-6">
												<input type="text" class="form-control m-input" name="copyright" value="{{ old('copyright', config('settings.copyright')) }}" placeholder="Copyright">
												@error('copyright')
												<div class="form-control-feedback">{{ $message }}</div>
												@enderror
											</div>
										</div>
										
										<div class="form-group m-form__group row @error('disqus_username') has-danger @enderror">
											<label class="col-lg-2 col-form-label">Disqus Username</label>
											<div class="col-lg-6">
												<input type="text" class="form-control m-input" name="disqus_username" value="{{ old('disqus_username', config('settings.disqus_username')) }}" placeholder="Disqus Username">
												@error('disqus_username')
												<div class="form-control-feedback">{{ $message }}</div>
												@enderror
											</div>
										</div>
										
										<div class="form-group m-form__group row @error('site_logo') has-danger @enderror">
											<label class="col-lg-2 col-form-label">Website Logo</label>
											<div class="col-lg-6">
												<input type="file" class="form-control m-input" accept="image/*" id="site_logo" name="site_logo" onchange="loadFile(event,'logoImg')" />
												@error('site_logo')
												<div class="form-control-feedback">{{ $message }}</div>
												@enderror
												<p></p>
												<div class="fileinput fileinput-exists">
													<div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;">
														@if (config('settings.site_logo') != null)
															<img src="{{ asset('application/storage/app/public/'.config('settings.site_logo')) }}" id="logoImg" style="max-height: 140px;">
														@else
															<img src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=no+image" id="logoImg" style="max-height: 140px;">
														@endif
													</div>
												</div>
											</div>
										</div>
										
										<div class="form-group m-form__group row @error('site_favicon') has-danger @enderror">
											<label class="col-lg-2 col-form-label">Website Favicon</label>
											<div class="col-lg-6">
												<input type="file" class="form-control m-input" accept="image/*" id="site_favicon" name="site_favicon" onchange="loadFile(event,'faviconImg')" />
												@error('site_favicon')
												<div class="form-control-feedback">{{ $message }}</div>
												@enderror
												<p></p>
												<div class="fileinput fileinput-exists">
													<div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;">
														@if (config('settings.site_favicon') != null)
															<img src="{{ asset('application/storage/app/public/'.config('settings.site_favicon')) }}" id="faviconImg" style="max-height: 140px;">
														@else
															<img src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=no+image" id="faviconImg" style="max-height: 140px;">
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
											<div class="col-lg-2"></div>
											<div class="col-lg-6">
												<button type="submit" class="btn btn-success m-btn m-btn--custom">Update</button>
												<a href="{{route('admin_dashboard')}}" class="btn btn-secondary m-btn m-btn--custom">Cancel</a>
											</div>
										</div>
									</div>
								</div>									
							</form>
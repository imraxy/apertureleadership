							<form class="m-form m-form--label-align-right " action="{{ route('admin.settings.update') }}"  method="POST" enctype="multipart/form-data">
								
								@method('PUT')
									
								@csrf
								
								<input type="hidden" name="current_request" value="2" />
								
								<div class="m-portlet__body">
								
									<div class="m-form__section m-form__section--first">
									
										<div class="form-group m-form__group row @error('meta_keywords') has-danger @enderror">
											<label class="col-lg-2 col-form-label">Meta Keywords</label>
											<div class="col-lg-6">
												<textarea class="form-control" name="meta_keywords" placeholder="Meta Keywords" rows="5">{{ old('meta_keywords', config('settings.meta_keywords')) }}</textarea>
												@error('meta_keywords')
												<div class="form-control-feedback">{{ $message }}</div>
												@enderror
											</div>
										</div>
										
										<div class="form-group m-form__group row @error('meta_description') has-danger @enderror">
											<label class="col-lg-2 col-form-label">Meta Description</label>
											<div class="col-lg-6">
												<textarea class="form-control" name="meta_description" placeholder="Meta Keywords" rows="5">{{ old('meta_description', config('settings.meta_description')) }}</textarea>
												@error('meta_description')
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
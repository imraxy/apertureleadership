@extends('admin.layouts.app')
@push('css')
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css" rel="stylesheet">

<style>
 .datetimepicker {
	 z-index: 9999!important;
 }
 </style>
@endpush
@section('subheader__title', 'Bulk Assign Access	 Code')
	
@section('content')
		
	<!--begin::Portlet-->
	<div class="m-portlet">
		<div class="m-portlet__head">
			<div class="m-portlet__head-caption">
				<div class="m-portlet__head-title">
					<h3 class="m-portlet__head-text">
						Bulk Assign Access Code
					</h3>
				</div>
			</div>
		</div>

		<!--begin::Form-->
		<!--form class="m-form m-form--fit m-form--label-align-right"-->
		<form method="post" class="m-form m-form--fit m-form--label-align-right" action="" enctype="multipart/form-data">
			@csrf
			<input type="hidden" value="{{$user_id}}" name="user_ids">
			<div class="m-portlet__body">
				<div class="m-form__section m-form__section--first">
					 
					<div class="form-group m-form__group row">
						<label class="col-form-label col-lg-2 col-sm-12">Assign Access Code for Users:</label>
						<div class="col-lg-7 col-md-7 col-sm-12">
						 
							<ul class="list-group">
							@foreach($userfind as $list)
							  <li class="list-group-item ">{{$list->name}}</li>
							 
							  @endforeach
							</ul>
							
						</div>						
					</div>  
					
					<div class="form-group m-form__group row @error('approval_code_time') has-danger @enderror">
						<label class="col-form-label col-lg-2 col-sm-12">Assign Start Date and Time</label>
						<div class="col-lg-7 col-md-7 col-sm-12">
							   <input type='text' class="form-control" name="approval_code_time" id='datetimepicker1'  />
						 @error('approval_code_time')
								<div class="form-control-feedback">{{ $message }}</div>
						@enderror
						</div>
					</div> 
					
					<div class="form-group m-form__group row @error('approval_code_time_end') has-danger @enderror">
						<label class="col-form-label col-lg-2 col-sm-12">Assign Expiry Date and Time</label>
						<div class="col-lg-7 col-md-7 col-sm-12">
							 <input type='text' class="form-control" name="approval_code_time_end" id='datetimepicker2'   />
						 @error('approval_code_time_end')
								<div class="form-control-feedback">{{ $message }}</div>
						@enderror
						</div>
					</div>  

						
					<div class="form-group m-form__group row @error('approval_code') has-danger @enderror">
						<label class="col-form-label col-lg-2 col-sm-12">Access Code</label>
						<div class="col-lg-7 col-md-7 col-sm-12">
							<input type="text" class="form-control m-input" id="approval_code" name="approval_code" value="	" maxlength="6" value="" placeholder="Access code"	 >
						 
						 @error('approval_code')
								<div class="form-control-feedback">{{ $message }}</div>
						@enderror
						</div>
					</div> 
					 
				 
					<div class="form-group m-form__group row ">
						<label class="col-form-label col-lg-2 col-sm-12"> </label>
						<div class="col-lg-7 col-md-7 col-sm-12">
						<input type="button" class="btn btn-success m-btn m-btn--custom gen" value="Generate access code">
						</div>
					</div>
					 
				</div>
			</div>
			
			<div class="m-portlet__foot m-portlet__foot--fit">
				<div class="m-form__actions m-form__actions">
					<div class="row">
						<div class="col-lg-10 ml-lg-auto">
							<button type="submit" class="btn btn-success m-btn m-btn--custom">Submit</button>
							<a href="{{route('admin.approval')}}" class="btn btn-secondary m-btn m-btn--custom">Cancel</a>
						</div>
					</div>
				</div>
			</div>
		</form>

		<!--end::Form-->
	</div>
@endsection
@push('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.0/js/bootstrap-datepicker.min.js"></script>

<script>
		$(".gen").click(function(){
		 
			var chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
			var string_length = 6;
			var randomstring = '';
			for (var i=0; i<string_length; i++) {
				var rnum = Math.floor(Math.random() * chars.length);
				randomstring += chars.substring(rnum,rnum+1);
			}
			$("#approval_code").val(randomstring);
		})
</script>
<script>
$( document ).ready(function() {
	$('#datetimepicker1').datetimepicker({
	startDate: new Date(),
	 format: 'dd-mm-yyyy hh:ii',
	});
    $('#datetimepicker2').datetimepicker({
	startDate: new Date(),
	 format: 'dd-mm-yyyy hh:ii',
	});

});
</script>
@endpush



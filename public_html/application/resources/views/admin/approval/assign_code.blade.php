@extends('admin.layouts.app')
@push('css')
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css" rel="stylesheet">

<style>
 .datetimepicker {
	 z-index: 9999!important;
 }
 </style>
@endpush

@section('subheader__title', 'Assign Approval Code')
	
@section('content')
	<!--begin::Portlet-->
	<div class="m-portlet">
		<div class="m-portlet__head">
			<div class="m-portlet__head-caption">
				<div class="m-portlet__head-title">
					<h3 class="m-portlet__head-text">
						Assign Approval Code
					</h3>
				</div>
			</div>
		</div>

		<!--begin::Form-->
		<!--form class="m-form m-form--fit m-form--label-align-right"-->
		<form method="post" class="m-form m-form--fit m-form--label-align-right" action="" enctype="multipart/form-data">
			@csrf
			<input type="hidden" value="{{$data->id}}" name="user_id">
			<div class="m-portlet__body">
				<div class="m-form__section m-form__section--first">
					<div class="form-group m-form__group row @error('name') has-danger @enderror">
						<label class="col-form-label col-lg-2 col-sm-12">User Name</label>
						<div class="col-lg-7 col-md-7 col-sm-12">
							<input type="text" class="form-control m-input" name="name" id="name" value="{{$data->name}}" placeholder="User Name" readonly>
						</div>
					</div>
					<div class="form-group m-form__group row @error('email') has-danger @enderror">
						<label class="col-form-label col-lg-2 col-sm-12">Email</label>
						<div class="col-lg-7 col-md-7 col-sm-12">
							<input type="email" class="form-control m-input" id="email" name="email" value="{{$data->email}}" placeholder="Email" autocomplete="email" readonly >
							 
						</div>
					</div>
					 
					<div class="form-group m-form__group row @error('approval_code_time') has-danger @enderror">
						<label class="col-form-label col-lg-2 col-sm-12">Assign Start Date and Time</label>
						<div class="col-lg-7 col-md-7 col-sm-12">
							  <!--input type="text" class="form-control m-input" id="approval_code_time" name="approval_code_time" value="{{$data->approval_code_time}}" placeholder="Assign Total minute" required readonly-->
								<input type='text' class="form-control" name="approval_code_time" id='datetimepicker1' value="{{($data->approval_code_create_time !="")?date('d-m-Y H:i',strtotime($data->approval_code_create_time)):""}}"  />
						 @error('approval_code_time')
								<div class="form-control-feedback">{{ $message }}</div>
						@enderror
						</div>
					</div> 
					
					<div class="form-group m-form__group row @error('approval_code_time_end') has-danger @enderror">
						<label class="col-form-label col-lg-2 col-sm-12">Assign Expiry Date and Time</label>
						<div class="col-lg-7 col-md-7 col-sm-12">
							  <!--input type="text" class="form-control m-input" id="approval_code_time" name="approval_code_time" value="{{$data->approval_code_time}}" placeholder="Assign Total minute" required readonly-->
								<input type='text' class="form-control" name="approval_code_time_end" id='datetimepicker2' value="{{($data->approval_code_end_time !="")?date('d-m-Y H:i',strtotime($data->approval_code_end_time)):""}}"  />
						 @error('approval_code_time_end')
								<div class="form-control-feedback">{{ $message }}</div>
						@enderror
						</div>
					</div> 

						
					<div class="form-group m-form__group row @error('approval_code') has-danger @enderror">
						<label class="col-form-label col-lg-2 col-sm-12">Approval Code</label>
						<div class="col-lg-7 col-md-7 col-sm-12">
							<input type="text" class="form-control m-input" id="approval_code" name="approval_code" maxlength="6" value="{{$data->approval_code}}" placeholder="Approval code" required	 >
						 
						 @error('approval_code')
								<div class="form-control-feedback">{{ $message }}</div>
						@enderror
						</div>
					</div> 
					
					<div class="form-group m-form__group row">
						<label class="col-form-label col-lg-2 col-sm-12">Status</label>
						<div class="col-lg-7 col-md-7 col-sm-12 mt-2">
									 <span style="width: 110px;" class="mt-2"><span class="m-badge  m-badge--{{$status_cls}} m-badge--wide">{{$status}}</span></span>
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
<!--script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script-->
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
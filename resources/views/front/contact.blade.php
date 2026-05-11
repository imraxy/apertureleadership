@extends('layouts.master')
@push('css')
<style>
.alert-success, .alert-warning{
	text-align: left;
}

</style>
@endpush
@section('content')
    
    <section class="main-banner">
       <div class="banner-inn">
           <img src="{{asset('content/images/testimonials-bg.jpg')}}" class="img-responsive" title="contact" alt="contact image">
        </div>
        <div class="aperture-page-title">
            <div class="container">
                <div class="row">
                    <div class="col-md-12"> 
                        <div class="slide-title-box">
                            <div class="page-title-heading">
                                <h1 class="title"> Contact Us</h1>
                            </div>
                            <div class="slide-breadcrumb-wrapper">
                                <p class="contact-text">Please get in touch by email or by using our contact form: <i><br> We look forward to hearing from you.</i>
                                </p>
                            </div>  
                        </div>
                    </div>
                </div> 
            </div>                    
        </div>
    </section>
    
	<!--<div class="contact-pageheader">
        <div class="container">
            <div class="row">
                <div class="ol-md-12 col-sm-12 col-xs-12">
                    <div class="contact-caption text-center">
                        <p class="contact-text">Here is a few approaches to get in touch with me. It would be ideal if you send an email, fill the contact form : <i><br> I'm looking forward to speaking with you.</i>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>-->
    
    <section class="ex-aboutsection">
        <div class="space-medium">
        <div class="container">
            <div class="row">
                 
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <hr>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="contact-info">
                        <h3 class="title-bold">Contact Info</h3>
                        <p>Please get in touch by email of via our contact form. We look forward to hearing from you.
                        </p>
                    </div>
                    <!--<div class="contact-section">-->
                    <!--    <div class="contact-icon"><i class="fa fa-map-marker"></i></div>-->
                    <!--    <div class="contact-info">-->
                    <!--        <p>{{ config('settings.address') }}</p>-->
                    <!--    </div>-->
                    <!--</div>-->
                    <div class="contact-section">
                        <div class="contact-icon"><i class="fa fa-envelope"></i></div>
                        <div class="contact-info">
                            <p><a href="mailto:contact@apertureleadership.com">contact@apertureleadership.com</a></p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="contact-form">
                        <h3 class="contact-info-title">Contact Us</h3>
                        <div class="row">
                            <div class="col-lg-12  col-xs-12">
								@if(session('success'))
								<div class="alert alert-success">
									Thank you for your enquiry. we will be in touch shortly.
								</div>
								@endif
								@if(session('error'))
								<div class="alert alert-warning">
									Thank you for your enquiry. we will be in touch shortly.
								</div>
								@endif
                                <form action="{{route('front.ajax_contact_enquiry')}}" method="post">
									@csrf
                                    <div class="form-group col-md-12 col-sm-12">
                                        <label class="control-label sr-only " for="Name"></label>
                                        <input id="name" type="text" name="name" placeholder="Name" value="{{old('name')}}" class="form-control" required>
                                    </div>
                                
                                    <div class="form-group col-md-12 col-sm-12">
                                        <label class="control-label sr-only " for="email"></label>
                                        <input id="email" type="email" name="email" placeholder="Email" value="{{old('email')}}" class="form-control" required>
                                    </div>
                                
                                    <div class="form-group col-md-12 col-sm-12">
                                        <label class="control-label sr-only " for="Phone"></label>
                                        <input id="phone" type="text" name="phone" onkeypress="return NumericValidation(event);" value="{{old('phone')}}" placeholder="Phone" class="form-control" required>
                                    </div>
									
									 <div class="form-group col-md-12 col-sm-12">
                                        <label class="control-label sr-only " for="service">Services</label>
										<select name="service" id="service" required class="form-control" style="margin-bottom: 23px;">
									     <option value="">Select Service</option>
										  <option value="Online Services">Online Services</option>
										  <option value="Aperture Card Sets">Aperture Card Sets</option>
										  <option value="Consulting & Workshops">Consulting & Workshops</option>
										</select>
                                    </div>
                                
                                    <div class="form-group col-md-12 col-sm-12 mb20">
                                        <label class="control-label sr-only" for="message"></label>
                                        <textarea class="form-control pdt20" id="message" name="message" rows="4" placeholder="Message">{{old('message')}}</textarea>
                                    </div>
                                    
									<div class="col-lg-12 col-md-12 col-sm-6 col-xs-12">
										<input type="submit" class="btn btn-primary btn-lg" value="Send message"/>
									</div>
								</form>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>

        </div>
    </div>
    </section>

@endsection

@push('js')
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
	<script>
		function NumericValidation(evt) {
			var charCode = (evt.which) ? evt.which : evt.keyCode;
			if (charCode > 31 && (charCode < 46 || charCode > 57) )
				return false;

			return true;
		}
		
	</script>
@endpush

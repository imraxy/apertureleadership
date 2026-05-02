@extends('layouts.master')
@push('css')
<style>
.alert-success, .alert-warning{
	text-align: left;
}

/* Elegant Contact Page Styling */
.contact-hero {
    background: linear-gradient(135deg, #0a0a0c 0%, #1a1a1e 100%);
    padding: 80px 0 60px;
    position: relative;
    overflow: hidden;
}

.contact-hero::before {
    content: '';
    position: absolute;
    top: -50%;
    left: -20%;
    width: 600px;
    height: 600px;
    background: radial-gradient(circle, rgba(212, 166, 90, 0.1) 0%, transparent 70%);
    border-radius: 50%;
}

.contact-hero .page-title {
    text-align: center;
    color: #ffffff;
    font-size: 48px;
    font-weight: 600;
    margin-bottom: 16px;
    position: relative;
    z-index: 1;
}

.contact-hero .page-subtitle {
    text-align: center;
    color: #a0a0a8;
    font-size: 18px;
    max-width: 600px;
    margin: 0 auto;
    position: relative;
    z-index: 1;
}

.contact-container {
    background: #0f0f12;
    padding: 80px 0;
}

.contact-layout {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 60px;
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 40px;
}

.contact-info-card {
    background: #151519;
    border-radius: 16px;
    padding: 40px;
    border: 1px solid #25252a;
}

.contact-info-card h3 {
    color: #ffffff;
    font-size: 28px;
    font-weight: 600;
    margin-bottom: 16px;
}

.contact-info-card > p {
    color: #a0a0a8;
    font-size: 16px;
    line-height: 1.7;
    margin-bottom: 32px;
}

.contact-item {
    display: flex;
    align-items: center;
    padding: 20px 0;
    border-bottom: 1px solid #25252a;
}

.contact-item:last-child {
    border-bottom: none;
}

.contact-icon {
    width: 50px;
    height: 50px;
    background: rgba(212, 166, 90, 0.1);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 20px;
    color: #d4a65a;
    font-size: 20px;
}

.contact-item p {
    color: #ffffff;
    font-size: 16px;
    margin: 0;
}

.contact-form-card {
    background: #151519;
    border-radius: 16px;
    padding: 40px;
    border: 1px solid #25252a;
}

.contact-form-card h3 {
    color: #ffffff;
    font-size: 28px;
    font-weight: 600;
    margin-bottom: 32px;
}

.form-group {
    margin-bottom: 20px;
}

.form-control {
    background: #1a1a1e;
    border: 1px solid #25252a;
    border-radius: 10px;
    padding: 16px 20px;
    color: #ffffff;
    font-size: 15px;
    width: 100%;
    transition: all 0.3s ease;
}

.form-control:focus {
    outline: none;
    border-color: #d4a65a;
    background: #1f1f24;
}

.form-control::placeholder {
    color: #6a6a70;
}

textarea.form-control {
    min-height: 120px;
    resize: vertical;
}

.btn-submit {
    background: #d4a65a;
    color: #0a0a0c;
    border: none;
    padding: 16px 40px;
    border-radius: 10px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    width: 100%;
}

.btn-submit:hover {
    background: #e4b66a;
    transform: translateY(-2px);
}

.alert {
    padding: 16px 20px;
    border-radius: 10px;
    margin-bottom: 24px;
}

.alert-success {
    background: rgba(40, 167, 69, 0.1);
    border: 1px solid rgba(40, 167, 69, 0.3);
    color: #28a745;
}

.alert-warning {
    background: rgba(255, 193, 7, 0.1);
    border: 1px solid rgba(255, 193, 7, 0.3);
    color: #ffc107;
}

@media (max-width: 992px) {
    .contact-layout {
        grid-template-columns: 1fr;
        padding: 0 20px;
    }
}
</style>
@endpush
@section('content')
    
    <!-- Elegant Hero -->
    <section class="contact-hero">
        <div class="container">
            <h1 class="page-title">Contact Us</h1>
            <p class="page-subtitle">Get in touch to learn more about Aperture workshops, consulting services, or card sets</p>
        </div>
    </section>
    
    <section class="contact-container">
        <div class="contact-layout">
            <!-- Contact Info -->
            <div class="contact-info-card">
                <h3>Contact Information</h3>
                <p>We'd love to hear from you. Reach out for workshops, consulting, or to learn more about our visual metaphor approach.</p>
                
                <div class="contact-item">
                    <div class="contact-icon">
                        <i class="fa fa-envelope"></i>
                    </div>
                    <p>{{ config('settings.webmaster_email') }}</p>
                </div>
            </div>
            
            <!-- Contact Form -->
            <div class="contact-form-card">
                <h3>Send a Message</h3>
                
                @if(session('success'))
                <div class="alert alert-success">
                    Thank you for your enquiry. We will be in touch shortly.
                </div>
                @endif
                
                @if(session('error'))
                <div class="alert alert-warning">
                    Thank you for your enquiry. We will be in touch shortly.
                </div>
                @endif
                
                <form action="{{route('front.ajax_contact_enquiry')}}" method="post">
                    @csrf
                    <div class="form-group">
                        <input type="text" name="name" placeholder="Your Name" value="{{old('name')}}" class="form-control" required>
                    </div>
                
                    <div class="form-group">
                        <input type="email" name="email" placeholder="Your Email" value="{{old('email')}}" class="form-control" required>
                    </div>
                
                    <div class="form-group">
                        <input type="text" name="phone" onkeypress="return NumericValidation(event);" value="{{old('phone')}}" placeholder="Your Phone" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <select name="service" required class="form-control">
                            <option value="">Select Service</option>
                            <option value="Online Services">Online Services</option>
                            <option value="Aperture Card Sets">Aperture Card Sets</option>
                            <option value="Consulting & Workshops">Consulting & Workshops</option>
                        </select>
                    </div>
                
                    <div class="form-group">
                        <textarea name="message" rows="4" placeholder="Your Message" class="form-control">{{old('message')}}</textarea>
                    </div>
                    
                    <button type="submit" class="btn-submit">Send Message</button>
                </form>
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

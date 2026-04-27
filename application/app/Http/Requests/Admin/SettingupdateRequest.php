<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SettingupdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $site_favicon = config('settings.site_favicon') != null ? 'nullable' : 'required_if:current_request,1';

        $site_logo = config('settings.site_logo') != null ? 'nullable' : 'required_if:current_request,1';
        
        return [
            'site_title' => 'required_if:current_request,1|string',
            'copyright' => 'required_if:current_request,1|string',
            'disqus_username' => 'required_if:current_request,1|string',
            'site_logo' => $site_favicon.'|image|mimes:jpeg,jpg,png',
            'site_favicon' => $site_favicon.'|mimes:jpeg,jpg,png,ico',
            'meta_keywords' => 'required_if:current_request,2|string',
            'meta_description' => 'required_if:current_request,2|string',
            'webmaster_email' => 'required_if:current_request,3|email|string',
            'address' => 'required_if:current_request,3|string',
            'phone' => 'required_if:current_request,3|numeric|regex:/^\d+(\.\d{1,2})?$/',
            'whatsapp' => 'required_if:current_request,3|numeric|regex:/^\d+(\.\d{1,2})?$/',
            'skype' => 'required_if:current_request,3|string',
            'facebook' => 'required_if:current_request,4|active_url',
            'twitter' => 'required_if:current_request,4|active_url',
            'instagram' => 'required_if:current_request,4|active_url',
            'youtube' => 'required_if:current_request,4|active_url',
            
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'site_title.required_if' => 'The :attribute field is required.',
            'site_title.string' => 'The :attribute must be a string.',
            'copyright.required_if' => 'The :attribute field is required.',
            'copyright.string' => 'The :attribute must be a string.',
            'disqus_username.required_if' => 'The :attribute field is required.',
            'disqus_username.string' => 'The :attribute must be a string.',
            'site_logo.required_if' => 'The :attribute field is required.',
            'site_logo.image' => 'The :attribute must be an image.',
            'site_logo.mimes' => 'The :attribute must be a file of type: jpeg, jpg, png.',
            'site_favicon.required_if' => 'The :attribute field is required.',
            'site_favicon.mimes' => 'The :attribute must be a file of type: jpeg, jpg, png, ico.',
            'meta_keywords.required_if' => 'The :attribute field is required.',
            'meta_keywords.string' => 'The :attribute must be a string.',
            'meta_description.required_if' => 'The :attribute field is required.',
            'meta_description.string' => 'The :attribute must be a string.',
            'webmaster_email.required_if' => 'The :attribute field is required.',
            'webmaster_email.string' => 'The :attribute must be a string.',
            'webmaster_email.email' => 'The :attribute must be a valid email address.',
            'address.required_if' => 'The :attribute field is required.',
            'address.string' => 'The :attribute must be a string.',
            'phone.required_if' => 'The :attribute field is required.',
            'phone.numeric' => 'The :attribute must be a number.',
            'phone.regex' => 'The :attribute format is invalid.',
            'whatsapp.required_if' => 'The :attribute field is required.',
            'whatsapp.numeric' => 'The :attribute must be a number.',
            'whatsapp.regex' => 'The :attribute format is invalid.',
            'skype.required_if' => 'The :attribute field is required.',
            'skype.string' => 'The :attribute must be a string.',
            'facebook.required_if' => 'The :attribute field is required.',
            'facebook.active_url' => 'The :attribute is not a valid URL.',
            'twitter.required_if' => 'The :attribute field is required.',
            'twitter.active_url' => 'The :attribute is not a valid URL.',
            'instagram.required_if' => 'The :attribute field is required.',
            'instagram.active_url' => 'The :attribute is not a valid URL.',
            'youtube.required_if' => 'The :attribute field is required.',
            'youtube.active_url' => 'The :attribute is not a valid URL.',
            
        ];
    }
}

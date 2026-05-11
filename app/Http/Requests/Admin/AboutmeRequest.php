<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\AboutUs;
use App\Rules\CheckaboutImage;

class AboutmeRequest extends FormRequest
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
        $get_about_image = AboutUs::first(['image']);

        $about_me_image = $get_about_image->image ? 'nullable' : 'required';
        
        return [
            'name' => 'required|string|max:191',
            'about_me_content' => 'required|string',
            'about_me_image' => $about_me_image.'|image|mimes:jpeg,jpg,png',
            'satisfied_client' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'meetings' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'sessions_have_done' => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/',
            'established_from' => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/',
        ];
    }
}

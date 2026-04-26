<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class AddpostRequest extends FormRequest
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
        return [
            //'category' => 'required|exists:blog_categories,id',
            'title' => 'required|string|max:191|unique:posts,title',            
            'description' => 'required|string',
            //'permalink' => 'required|string',
            'featured_image' => 'nullable|image|mimes:jpeg,jpg,png',
        ];
    }
}

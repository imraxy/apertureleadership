<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class AlbumaddRequest extends FormRequest
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
            'category' => 'required|exists:album_categories,id',
            'title' => 'required|string|max:191|unique:album_sessions,title',            
            'description' => 'required|string',
            //'permalink' => 'required|string',
            'featured_image' => 'required|image|mimes:jpeg,jpg,png',
            //'gallery_images' => 'required|array',
            //'gallery_images.*' => 'nullable|image|mimes:jpeg,jpg,png',
            
        ];
    }
}

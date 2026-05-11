<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\AlbumSession;

class AlbumupdateRequest extends FormRequest
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

        $get_featured_image = AlbumSession::where('id', $this->id)->first(['featured_image']);

        $required_featured_image = $get_featured_image->featured_image ? 'nullable' : 'required';

        return [
            'category' => 'required|exists:album_categories,id',
            'title' => 'required|string|max:191|unique:album_sessions,title,'.$this->id,            
            'description' => 'required|string',
            //'permalink' => 'required|string',
            'featured_image' => $required_featured_image.'|image|mimes:jpeg,jpg,png',
            //'gallery_images' => 'nullable|array',
            //'gallery_images.*' => 'nullable|image|mimes:jpeg,jpg,png',
        ];
    }
}

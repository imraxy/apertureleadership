<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Post;

class UpdatepostRequest extends FormRequest
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
        $get_featured_image = Post::where('id', $this->id)->first(['featured_image']);

        $required_featured_image = $get_featured_image->featured_image ? 'nullable' : 'required';
        
        return [
            //'category' => 'required|exists:blog_categories,id',
            'title' => 'required|string|max:191|unique:posts,title,'.$this->id,            
            'description' => 'required|string',
            //'permalink' => 'required|string',
            'featured_image' => 'nullable|image|mimes:jpeg,jpg,png',
        ];
    }
}

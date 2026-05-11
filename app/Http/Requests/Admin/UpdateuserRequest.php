<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateuserRequest extends FormRequest
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
            //'user_name' => 'required|string|max:191|unique:users,user_name,'.$this->id,
            'user_name' => 'required|string|max:191',
            'email' => 'required|string|email|max:191|unique:users,email,'.$this->id,
            'user_image' => 'nullable|image|mimes:jpeg,jpg,png',
            'password' => 'nullable|string|min:8|max:16',
        ];
    }
}

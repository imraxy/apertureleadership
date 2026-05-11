<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class PackageaddRequest extends FormRequest
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
            'title' => 'required|string|max:191|unique:session_packages,title',            
            'price' => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/',
            'currency' => 'required|string',
            'discount' => 'nullable|numeric|regex:/^\d+(\.\d{1,2})?$/',
            'details' => 'required|string',
        ];
    }
}

<?php

namespace App\Http\Requests;

class StoreItem extends FormRequest
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
            'description'       => 'required|min:5|max:20',
            'hashtags'          => 'required|min:1|max:20',
            'hashtags.*'        => 'required|string|distinct|min:2',
            'steps'             => 'required|between:1,20',
            'steps.*'           => 'required|string|min:10|max:300',
            'materials'         => 'array',
            'materials.*'       => 'required|string|min:3|max:255',
            'video_tutorial'    => 'url',
            'tutorial'          => 'max:16777215',
            'minimum_price'     => 'numeric|min:0',
            'maximum_price'     => 'numeric|min:0|gt:minimum_price'
        ];
    }
}

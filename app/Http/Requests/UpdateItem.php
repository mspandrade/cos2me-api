<?php
namespace App\Http\Requests;

use App\Http\Requests\Traits\ItemOwnerAuthorization;

class UpdateItem extends FormRequest
{
    use ItemOwnerAuthorization;
    
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        
        return [
            'description'       => 'min:5|max:20',
            'hashtags'          => 'min:1|max:20',
            'hashtags.*'        => 'string|distinct|min:2',
            'steps'             => 'between:1,20',
            'steps.*'           => 'string|min:10|max:300',
            'materials'         => 'array',
            'materials.*'       => 'required|string|min:3|max:255',
            'video_tutorial'    => 'url',
            'tutorial'          => 'max:16777215',
            'minimum_price'     => 'numeric|min:0',
            'maximum_price'     => 'numeric|min:0|gt:minimum_price'
        ];
    }
}

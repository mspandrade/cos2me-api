<?php

namespace App\Http\Requests;

use App\Http\Requests\Traits\ItemOwnerAuthorization;

class UpdateImagesItem extends FormRequest
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
            'files.*'   => 'mimes:jpeg',
            'remove'    => 'array|min:0',
            'remove.*'  => 'string|min:36|max:50',
            'order.*'     => 'string'
        ];
    }
}

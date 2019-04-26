<?php

namespace App\Http\Requests;

use App\Http\Requests\Traits\ListOwnerAuthorization;

class UpdateImagesList extends FormRequest
{
    use ListOwnerAuthorization;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $orderSize = count($this->request->get('order', []));
        
        return [
            'files.*'   => 'mimes:jpeg',
            'remove'    => 'array|min:0',
            'remove.*'  => 'string|min:36|max:50',
            'order.*'   => 'string',
            'reference' => "array|size:{$orderSize}"
        ];
    }
}

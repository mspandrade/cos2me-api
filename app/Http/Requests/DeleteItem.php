<?php
namespace App\Http\Requests;

use App\Http\Requests\Traits\ItemOwnerAuthorization;

class DeleteItem extends FormRequest
{
    use ItemOwnerAuthorization;
    
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        
        return [];
    }
}

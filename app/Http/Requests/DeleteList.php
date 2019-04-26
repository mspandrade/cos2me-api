<?php
namespace App\Http\Requests;

use App\Http\Requests\Traits\ListOwnerAuthorization;

class DeleteList extends FormRequest
{
    use ListOwnerAuthorization;
    
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

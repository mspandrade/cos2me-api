<?php
namespace App\Http\Requests;

use App\Http\Requests\Traits\PostedOwnerListAuthorization;

class StoreInventoryItemsList extends FormRequest {
    
    use PostedOwnerListAuthorization;
    
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
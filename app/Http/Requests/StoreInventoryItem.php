<?php
namespace App\Http\Requests;

use App\Http\Requests\Traits\PostedItemAuthorization;

class StoreInventoryItem extends FormRequest {
    
    use PostedItemAuthorization;
    
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
<?php
namespace App\Http\Requests\Traits;

trait PostedItemAuthorization {
    
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $validator = validator(
            [ 'id'  => $this->route('id') ],
            [ 'id'  => 'required|postedOrOwner:items' ]
            );
        
        $validator->validate();
        
        return $validator->valid();
    }
} 
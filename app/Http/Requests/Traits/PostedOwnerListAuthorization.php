<?php
namespace App\Http\Requests\Traits;

trait PostedOwnerListAuthorization {
    
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $validator = validator(
            [ 'id'  => $this->route('id') ],
            [ 'id'  => 'required|postedOrOwner:lists' ]
            );
        
        $validator->validate();
        
        return $validator->valid();
    }
} 
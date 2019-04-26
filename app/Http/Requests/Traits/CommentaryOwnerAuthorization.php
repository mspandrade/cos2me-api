<?php
namespace App\Http\Requests\Traits;

trait CommentaryOwnerAuthorization {
    
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $validator = validator(
            [ 'id'  => $this->route('id') ],
            [ 'id'  => 'required|owner:commentaries' ]
            );
        
        $validator->validate();
        
        return $validator->valid();
    }
} 
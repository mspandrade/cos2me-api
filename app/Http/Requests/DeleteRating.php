<?php
namespace App\Http\Requests;

use App\Http\Requests\Traits\RatingOwnerAuthorization;

class DeleteRating extends FormRequest
{
    use RatingOwnerAuthorization;
    
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

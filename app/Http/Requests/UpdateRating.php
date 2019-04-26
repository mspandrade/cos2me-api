<?php
namespace App\Http\Requests;

use App\Http\Requests\Traits\RatingOwnerAuthorization;

class UpdateRating extends FormRequest
{
    use RatingOwnerAuthorization;
    
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'description'   => 'min:5|max:20|string',
            'value'         => 'required|min:0|max:50|int'
        ];
    }
}

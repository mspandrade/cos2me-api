<?php
namespace App\Http\Requests;


use App\Http\Requests\Traits\ListOwnerAuthorization;

class UpdateList extends FormRequest
{
    use ListOwnerAuthorization;
    
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'hashtags'          => 'min:1|max:20',
            "hashtags.*"        => 'string|distinct|min:2',
            'items'             => 'min:1|max:100',
            'items.*'           => 'required|numeric|min:1|exists:items,id|postedOrOwner:items',
            'character'         => 'min:1',
            'character.name'    => 'string|min:2',
            'character.id'      => 'numeric|exists:characters,id',
            'minimum_age'       => 'numeric|min:12|max:150'
        ];
    }
}

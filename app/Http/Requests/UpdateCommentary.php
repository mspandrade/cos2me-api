<?php
namespace App\Http\Requests;


use App\Http\Requests\Traits\CommentaryOwnerAuthorization;

class UpdateCommentary extends FormRequest
{
    use CommentaryOwnerAuthorization;
    
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'content'   => 'required|min:1|max:255'
        ];
    }
}

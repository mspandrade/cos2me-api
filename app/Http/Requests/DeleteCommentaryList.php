<?php
namespace App\Http\Requests;

use App\Http\Requests\Traits\CommentaryOwnerAuthorization;

class DeleteCommentaryList extends FormRequest
{
    use CommentaryOwnerAuthorization;
    
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

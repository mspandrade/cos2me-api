<?php
namespace App\Http\Requests;

class SearchClient extends FormRequest {

    
    public function authorize(){
        return true;
    }
    
    public function rules(){
        return [
            'content'   => 'required|min:0|string'
        ];   
    }    
}
<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class StoreClient extends FormRequest
{
    private const MIN_AGE = 12;
    
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        Validator::extend('olderThan', function($attribute, $value, $parameters) {
            
            $minAge = $parameters[0];
            
            $from = Carbon::createFromFormat('Y-m-d', $value);
            
            return (new Carbon())->diffInYears($from) >= $minAge;
        }, sprintf('The :attribute must be at least %s years apart', self::MIN_AGE));
        
        return [
            'name'          => 'required|max:190',
            'email'         => 'required|unique:users|max:190|email',
            'password'      => 'required|max:20',
            'username'      => 'required|unique:users|max:45',
            'date_birth'    => 'required|date_format:Y-m-d|olderThan:' . self::MIN_AGE
        ];
    }
}

<?php

namespace App\Http\Requests;

class PostRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() : bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() : array
    {
        return [
            'title'       => 'required|string|min:5,max:50',
            'description' => 'required|string|min:5,max:250',
            'body'        => 'required|string|min:50,max:1000',
        ];
    }
}

<?php

namespace App\Http\Requests;


class ProfileRequest extends Request
{

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
        return array_merge(['name' => 'required|max:255', 'image' => 'image|max:2048'], config('profile.rules', []));
    }
}

<?php

namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;

class RecitationRequest extends FormRequest
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
        return [
            'description'  => 'required|max:255',
            'sura_id'      => 'required|exists:suwar,id',
            'narration_id' => 'required|exists:narrations,id',
            'from_verse'   => 'required|exists:verses,id',
            'to_verse'     => 'required|exists:verses,id',
            'file'         => 'required|file',
            'mentions'     => 'array',
            'mentions.*'   => 'exists:users,id',

        ];
    }
}

<?php

namespace App\Http\Requests;


use Illuminate\Validation\Rule;

class StoreRecitationRequest extends Request
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
        $sura_id = $this->get('sura_id');

        return [
            'description'  => 'max:255',
            'sura_id'      => 'required|exists:suwar,id',
            'narration_id' => 'required|exists:narrations,id',
            'from_verse'   => [
                'required',
                Rule::exists('verses', 'id')->where(function ($query) use ($sura_id)
                {
                    $query->where('sura_id', $sura_id);
                }),
            ],
            'to_verse'     => [
                'required',
                Rule::exists('verses', 'id')->where(function ($query) use ($sura_id)
                {
                    $query->where('sura_id', $sura_id);
                }),
            ],
            // TODO add a validation on the type of file and add a size limit
            'file'         => 'required|file',
            'mentions'     => 'array',
            'mentions.*'   => 'exists:users,id',
        ];
    }
}

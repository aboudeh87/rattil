<?php

namespace App\Http\Requests;


use Illuminate\Validation\Rule;

class UpdateRecitationRequest extends Request
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $model = $this->route()->parameter('model');

        return $model->user_id === auth('api')->id();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'description'  => 'max:255',
            'sura_id'      => 'required|exists:suwar,id',
            'narration_id' => 'required|exists:narrations,id',
            'from_verse'   => [
                'required',
                Rule::exists('verses', 'id')->where(function ($query)
                {
                    $query->where('sura_id', $this->get('sura_id'));
                }),
            ],
            'to_verse'     => [
                'required',
                Rule::exists('verses', 'id')->where(function ($query)
                {
                    $query->where('sura_id', $this->get('sura_id'));
                }),
            ],
            'mentions'     => 'array',
            'mentions.*'   => 'exists:users,id',
        ];
    }
}

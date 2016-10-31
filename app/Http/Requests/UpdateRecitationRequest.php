<?php

namespace App\Http\Requests;


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
            'description' => 'max:255',
            'mentions'    => 'array',
            'mentions.*'  => 'exists:users,id',
        ];
    }
}

<?php

namespace Modules\EstimateManager\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use \Illuminate\Http\Request;

class EditSubActivityRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(Request $request)
    {
        return [
            'project_id'        => 'bail|required',
            'main_activity_id'  => 'bail|required',
            'activity'          => 'bail|required|max:150',
            'quantity'          => 'bail|required|numeric',
            'unit'              => 'bail|nullable|max:100',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        \Illuminate\Support\Facades\Session::flash('ValidatorError', 'Oops something went wrong. Please check the required fields and complete them.');
        return parent::failedValidation($validator);
    }
}
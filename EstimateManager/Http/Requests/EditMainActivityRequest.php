<?php

namespace Modules\EstimateManager\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use \Illuminate\Http\Request;

class EditMainActivityRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(Request $request)
    {
        return [
            'project_id'        => 'bail|required|max:150',
            'activity'          => 'bail|required|max:150',
            'area'              => 'bail|required|max:150',
            'level'             => 'bail|required|max:150',
            'quantity'          => 'bail|required|numeric',
            'unit_qty'          => 'bail|nullable|numeric',
            'unit'              => 'bail|nullable|max:150',
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
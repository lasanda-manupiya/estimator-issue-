<?php

namespace Modules\EstimateManager\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use \Illuminate\Http\Request;

class GeneralRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(Request $request)
    {
        return [
            'main_activity.*.main_code'         => 'required|max:150',
            'main_activity.*.activity'          => 'required|max:150',
            'main_activity.*.area'              => 'required|max:150',
            'main_activity.*.level'             => 'required|max:150',
            'main_activity.*.quantity'          => 'required|numeric',
            'main_activity.*.rate'              => 'required|numeric',
            'main_activity.*.total'             => 'required|numeric',
            'main_activity.*.unit_qty'          => 'required|numeric',
            'main_activity.*.unit_rate'         => 'required|numeric',
            'main_activity.*.unit'              => 'required|numeric',
            'sub_activity.*.sub_code'           => 'required|max:150',
            'sub_activity.*.activity'           => 'required|max:150',
            'sub_activity.*.quantity'           => 'required|numeric',
            'sub_activity.*.rate'               => 'required|numeric',
            'sub_activity.*.total'              => 'required|numeric',
            'activity.*.item_code'              => 'required|max:150',
            'activity.*.activity'               => 'required|max:150',
            'activity.*.level'                  => 'required|max:150',
            'activity.*.quantity'               => 'required|numeric',
            'activity.*.rate'                   => 'required|numeric',
            'activity.*.selling_cost'           => 'required|numeric',
            'activity.*.profit'                 => 'required|numeric',
            'activity.*.total'                  => 'required|numeric',
        ];
    }

     /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'team_type.*.required' => 'The team type filed is required.',
            'team_for.*.required' => 'The team for filed is required.',
            'regions.*.required' => 'The regions filed is required.',
            'profile_image.mimes'  => 'Invalid File format !',
            'profile_image.max'  => 'Size should be less than 1MB !',
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
<?php

namespace Modules\PurchaseManager\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use \Illuminate\Http\Request;

class UpdatePurchaseOrderRequest extends FormRequest {

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(Request $request) {
        return [
            'delivery_date'=> 'bail|required',
            'delivery_time'=> 'bail|required',
            'delivery_address'=> 'bail|required|max:800',
            'notes'=> 'bail|nullable|max:500',
            'activities.*.activity'=> 'bail|nullable|max:100',
            'activities.*.unit'=> 'bail|nullable|max:100',
            'activities.*.quantity'=> 'bail|nullable|numeric',
            'activities.*.rate'=> 'bail|nullable|numeric',
            'activities.*.total'=> 'bail|nullable|numeric',
            'carriage_costs'=> 'bail|nullable|numeric',
            'c_of_c'=> 'bail|nullable|numeric',
            'other_costs'=> 'bail|nullable|numeric',
            'grand_total'=> 'bail|nullable|numeric',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages() {
        return [
            'project_id.required' => 'The project filed is required.',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() {
        return true;
    }

}

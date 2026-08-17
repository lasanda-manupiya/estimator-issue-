<?php

namespace Modules\PurchaseManager\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use \Illuminate\Http\Request;

class SavePurchaseOrderRequest extends FormRequest {

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(Request $request) {
        return [
            'project_id' => 'bail|required',
            'supplier_id' => 'bail|required',
            'area' => 'bail|required',
            'level' => 'bail|required',
            'sub_code' => 'bail|required',
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
            'supplier_id.required' => 'The supplier filed is required.',
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

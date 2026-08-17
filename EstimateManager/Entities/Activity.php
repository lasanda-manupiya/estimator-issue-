<?php

namespace Modules\EstimateManager\Entities;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model {

    protected $fillable = [
        'sub_activity_id',
        'item_code',
        'activity',
        'unit',
        'quantity',
        'rate',
        'selling_cost',
        'total',
        'mhr_role',
        'mhr_status',
        'co',
        'totalco'
    ];

    /**
     * Get the materials of the quotation.
     */
    public function quotationMaterials()
    {
        return $this->hasMany('Modules\PurchaseManager\Entities\QuotationMaterial');
    }

    /**
     * Get the project for the estimate.
     */
    public function subActivity()
    {
        return $this->belongsTo('Modules\EstimateManager\Entities\SubActivity');
    }

    /**
     * Get the profit.
     *
     * @return 
     */
    public function getProfitAttribute()
    {
        return ($this->selling_cost-$this->rate);
    }

}

<?php

namespace Modules\PurchaseManager\Entities;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model {

    protected $fillable = [
        'purchase_id',
        'activity_id',
        'activity',
        'unit',
        'quantity',
        'rate',
        'total',
        'photo',
    ];
   

    /**
     * Get the purchase for the order.
     */
    public function purchase()
    {
        return $this->belongsTo('Modules\PurchaseManager\Entities\Purchase');
    }

    /**
     * Get the activity for the order.
     */
    public function activityOfOrder()
    {
        return $this->belongsTo('Modules\EstimateManager\Entities\Activity', 'activity_id');
    }

}

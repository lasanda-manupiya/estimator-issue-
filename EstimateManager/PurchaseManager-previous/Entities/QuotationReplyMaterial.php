<?php

namespace Modules\PurchaseManager\Entities;

use Illuminate\Database\Eloquent\Model;

class QuotationReplyMaterial extends Model {

    protected $fillable = [
        'quotation_id',
        'supplier_id',
        'activity_id',
        'activity',
        'unit',
        'quantity',
        'rate',
        'total',
        'photo',
    ];

    /**
     * Get the quotation for the material reply.
     */
    public function quotation()
    {
        return $this->belongsTo('Modules\PurchaseManager\Entities\Quotation');
    }

    /**
     * Get the activity for the material.
     */
    public function activityOfMaterial()
    {
        return $this->belongsTo('Modules\EstimateManager\Entities\Activity', 'activity_id');
    }

    /**
     * The suppliers that belong to the material reply.
     */
    public function supplier()
    {
        return $this->belongsTo('App\User', 'supplier_id');
    }

}

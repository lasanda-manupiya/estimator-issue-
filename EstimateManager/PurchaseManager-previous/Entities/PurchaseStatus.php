<?php

namespace Modules\PurchaseManager\Entities;

use Illuminate\Database\Eloquent\Model;

class PurchaseStatus extends Model {

    protected $fillable = [
        'purchase_id',
        'supplier_id',
        'status'
    ];

    /**
     * Get the purchase for the seen purchase.
     */
    public function purchase()
    {
        return $this->belongsTo('Modules\PurchaseManager\Entities\Purchase', 'purchase_id');
    }

    /**
     * The supplier that belong to the purchase status.
     */
    public function supplier()
    {
        return $this->belongsTo('App\User', 'supplier_id');
    }

}

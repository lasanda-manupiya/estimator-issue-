<?php

namespace Modules\PurchaseManager\Entities;

use Illuminate\Database\Eloquent\Model;

class SeenPurchase extends Model {

    protected $fillable = [
        'purchase_id',
        'user_id',
    ];

    /**
     * Get the purchase for the seen purchase.
     */
    public function purchase()
    {
        return $this->belongsTo('Modules\PurchaseManager\Entities\Purchase', 'purchase_id');
    }

    /**
     * The user that belong to the seen purchase.
     */
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

}

<?php

namespace Modules\PurchaseManager\Entities;

use Illuminate\Database\Eloquent\Model;

class PurchaseInvoiceHistory extends Model {

    protected $fillable = [
        'purchase_id',
        'approver_id',
        'revision_no',
        'invoice_no',
        'invoice_amount',
        'invoice_date'
    ];

    protected $dates = [
        'invoice_date'
    ];
   

    /**
     * Get the purchase for the order.
     */
    public function purchase()
    {
        return $this->belongsTo('Modules\PurchaseManager\Entities\Purchase', 'purchase_id');
    }

    /**
     * Get the activity for the order.
     */
    public function approver()
    {
        return $this->belongsTo('App\User', 'approver_id');
    }

}

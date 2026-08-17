<?php

namespace Modules\PurchaseManager\Entities;

use Illuminate\Database\Eloquent\Model;

class SeenQuotation extends Model {

    protected $fillable = [
        'quotation_id',
        'user_id',
    ];

    /**
     * Get the quotation for the seen quotation.
     */
    public function quotation()
    {
        return $this->belongsTo('Modules\PurchaseManager\Entities\Quotation', 'quotation_id');
    }

    /**
     * The user that belong to the seen quotation.
     */
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

}

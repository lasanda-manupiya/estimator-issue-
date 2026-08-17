<?php

namespace Modules\PurchaseManager\Entities;

use Illuminate\Database\Eloquent\Model;

class QuotationReply extends Model {

    protected $fillable = [
        'quotation_id',
        'supplier_id',
        'sender_id',
        'carriage_costs',
        'c_of_c',
        'other_costs',
        'notes',
    ];

    /**
     * Get the materials of the quotation.
     */
    public function materials()
    {
        return $this->hasMany('Modules\PurchaseManager\Entities\QuotationMaterial');
    }

    /**
     * The suppliers that belong to the quotation.
     */
    public function supplier()
    {
        return $this->belongsTo('App\User', 'supplier_id');
    }

    /**
     * Get the sender for the reply.
     */
    public function sender()
    {
        return $this->belongsTo('App\User', 'sender_id');
    }
   

    /**
     * Get the quotation for the reply.
     */
    public function quotation()
    {
        return $this->belongsTo('Modules\PurchaseManager\Entities\Quotation', 'quotation_id');
    }

}

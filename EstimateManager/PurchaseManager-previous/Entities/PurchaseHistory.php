<?php

namespace Modules\PurchaseManager\Entities;

use Illuminate\Database\Eloquent\Model;

class PurchaseHistory extends Model {

    protected $fillable = [
        'purchase_id',
        'project_id',
        'main_activity_id',
        'sub_activity_id',
        'supplier_id',
        'purchase_no',
        'invoice_no',
        'revision_no',
        'delivery_address',
        'delivery_date',
        'delivery_time',
        'carriage_costs',
        'c_of_c',
        'other_costs',
        'grand_total',
        'invoice_details',
        'notes',
    ];
   

    protected $dates = [
        'delivery_date',
    ];


    /**
     * Get the purchase for the history.
     */
    public function purchase()
    {
        return $this->belongsTo('Modules\PurchaseManager\Entities\Purchase');
    }
   
    /**
     * Get the project for the purchase.
     */
    public function project()
    {
        return $this->belongsTo('Modules\ProjectManager\Entities\Project');
    }

    /**
     * Get the main activity for the purchase.
     */
    public function mainActivity()
    {
        return $this->belongsTo('Modules\EstimateManager\Entities\MainActivity');
    }

    /**
     * Get the sub activity for the purchase.
     */
    public function subActivity()
    {
        return $this->belongsTo('Modules\EstimateManager\Entities\SubActivity');
    }

    /**
     * Get the supplier for the purchase.
     */
    public function supplier()
    {
        return $this->belongsTo('App\User', 'supplier_id');
    }

}

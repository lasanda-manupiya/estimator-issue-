<?php

namespace Modules\PurchaseManager\Entities;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model {

    protected $fillable = [
		'project_id',
        'main_activity_id',
        'sub_activity_id',
        'supplier_id',
        'delivery_address',
        'delivery_date',
        'delivery_time',
        'carriage_costs',
        'c_of_c',
        'other_costs',
        'grand_total',
        'purchase_no',
        'invoice_no',
        'revision_no',
        'invoice_details',
        'notes',
    ];

    protected $dates = [
        'delivery_date',
    ];

    /**
     * Get the purchase histories of the purchase.
     */
    public function purchaseHistories()
    {
        return $this->hasMany('Modules\PurchaseManager\Entities\PurchaseHistory');
    }

    /**
     * Get the orders of the purchase.
     */
    public function orders()
    {
        return $this->hasMany('Modules\PurchaseManager\Entities\PurchaseOrder');
    }

     /**
     * Get the order histories of the purchase.
     */
    public function orderHistories()
    {
        return $this->hasMany('Modules\PurchaseManager\Entities\PurchaseOrderHistory');
    }

    /**
     * Get the invoices of the purchase.
     */
    public function invoices()
    {
        return $this->hasMany('Modules\PurchaseManager\Entities\PurchaseInvoice');
    }

    /**
     * Get the invoice histories of the purchase.
     */
    public function invoiceHistories()
    {
        return $this->hasMany('Modules\PurchaseManager\Entities\PurchaseInvoiceHistory');
    }

    /**
     * Get the statuses of the purchase.
     */
    public function statuses()
    {
        return $this->hasMany('Modules\PurchaseManager\Entities\PurchaseStatus');
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
	 /**
     * Get the project for the purchase.
     */
    public function Projectuser()
    {
        return $this->belongsTo('App\Projectuser','project_id');
    }


}

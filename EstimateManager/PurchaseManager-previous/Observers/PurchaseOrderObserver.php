<?php

namespace Modules\PurchaseManager\Observers;

use Modules\PurchaseManager\Entities\PurchaseOrder;
use Modules\PurchaseManager\Entities\PurchaseOrderHistory;

class PurchaseOrderObserver {

    /**
     * Handle the PurchaseOrder "created" event.
     *
     * @param  use Modules\PurchaseManager\Entities\PurchaseOrder  $purchaseOrder
     * @return void
     */
    public function saving(PurchaseOrder $purchaseOrder)
    {
        //
    }

    /**
     * Handle the PurchaseOrder "created" event.
     *
     * @param  use Modules\PurchaseManager\Entities\PurchaseOrder  $purchaseOrder
     * @return void
     */
    public function saved(PurchaseOrder $purchaseOrder)
    {
        //
    }

    /**
     * Handle the PurchaseOrder "creating" event.
     *
     * @param  use Modules\PurchaseManager\Entities\PurchaseOrder  $purchaseOrder
     * @return void
     */
    public function creating(PurchaseOrder $purchaseOrder)
    {
        //
    }

    /**
     * Handle the PurchaseOrder "created" event.
     *
     * @param  use Modules\PurchaseManager\Entities\PurchaseOrder  $purchaseOrder
     * @return void
     */
    public function created(PurchaseOrder $purchaseOrder)
    {
        //
    }

    /**
     * Handle the PurchaseOrder "updating" event.
     *
     * @param  use Modules\PurchaseManager\Entities\PurchaseOrder  $purchaseOrder
     * @return void
     */
    public function updating(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrderHistory                       = new PurchaseOrderHistory();
        $purchaseOrderHistory->purchase_id          = $purchaseOrder->purchase_id;
        $purchaseOrderHistory->activity_id          = $purchaseOrder->activity_id;
        $purchaseOrderHistory->revision_no          = $purchaseOrder->purchase->revision_no;
        $purchaseOrderHistory->activity             = $purchaseOrder->activity;
        $purchaseOrderHistory->unit                 = $purchaseOrder->unit;
        $purchaseOrderHistory->quantity             = $purchaseOrder->quantity;
        $purchaseOrderHistory->rate                 = $purchaseOrder->rate;
        $purchaseOrderHistory->total                = $purchaseOrder->total;
        $purchaseOrderHistory->photo                = $purchaseOrder->photo;
        $purchaseOrderHistory->save();
    }

    /**
     * Handle the PurchaseOrder "updated" event.
     *
     * @param  use Modules\PurchaseManager\Entities\PurchaseOrder  $purchaseOrder
     * @return void
     */
    public function updated(PurchaseOrder $purchaseOrder)
    {
        //
    }

    /**
     * Handle the PurchaseOrder "deleting" event.
     *
     * @param  use Modules\PurchaseManager\Entities\PurchaseOrder  $purchaseOrder
     * @return void
     */
    public function deleting(PurchaseOrder $purchaseOrder)
    {
        //
    }

    /**
     * Handle the User "PurchaseOrder" event.
     *
     * @param  use Modules\PurchaseManager\Entities\PurchaseOrder  $purchaseOrder
     * @return void
     */
    public function deleted(PurchaseOrder $purchaseOrder)
    {
        //
    }

    /**
     * Handle the PurchaseOrder "forceDeleted" event.
     *
     * @param  use Modules\PurchaseManager\Entities\PurchaseOrder  $purchaseOrder
     * @return void
     */
    public function forceDeleted(PurchaseOrder $purchaseOrder)
    {
        //
    }

}

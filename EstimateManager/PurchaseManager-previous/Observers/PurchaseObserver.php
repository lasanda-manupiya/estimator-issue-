<?php

namespace Modules\PurchaseManager\Observers;

use Modules\PurchaseManager\Entities\Purchase;
use Modules\PurchaseManager\Entities\PurchaseHistory;

class PurchaseObserver {

    /**
     * Handle the Purchase "created" event.
     *
     * @param  use Modules\PurchaseManager\Entities\Purchase  $purchase
     * @return void
     */
    public function saving(Purchase $purchase)
    {
        //
    }

    /**
     * Handle the Purchase "created" event.
     *
     * @param  use Modules\PurchaseManager\Entities\Purchase  $purchase
     * @return void
     */
    public function saved(Purchase $purchase)
    {
        //
    }

    /**
     * Handle the Purchase "creating" event.
     *
     * @param  use Modules\PurchaseManager\Entities\Purchase  $purchase
     * @return void
     */
    public function creating(Purchase $purchase)
    {
        //
    }

    /**
     * Handle the Purchase "created" event.
     *
     * @param  use Modules\PurchaseManager\Entities\Purchase  $purchase
     * @return void
     */
    public function created(Purchase $purchase)
    {
        //
    }

    /**
     * Handle the Purchase "updating" event.
     *
     * @param  use Modules\PurchaseManager\Entities\Purchase  $purchase
     * @return void
     */
    public function updating(Purchase $purchase)
    {
        $purchaseHistory                      = new PurchaseHistory();
        $purchaseHistory->purchase_id         = $purchase->id;
        $purchaseHistory->project_id          = $purchase->project_id;
        $purchaseHistory->main_activity_id    = $purchase->main_activity_id;
        $purchaseHistory->sub_activity_id     = $purchase->sub_activity_id;
        $purchaseHistory->supplier_id         = $purchase->supplier_id;
        $purchaseHistory->revision_no         = ($purchase->getOriginal('revision_no')+1);
        $purchaseHistory->purchase_no         = $purchase->purchase_no;
        $purchaseHistory->delivery_date       = $purchase->delivery_date;
        $purchaseHistory->delivery_time       = $purchase->delivery_time;
        $purchaseHistory->delivery_address    = $purchase->delivery_address;
        $purchaseHistory->carriage_costs      = $purchase->carriage_costs ?? 0;
        $purchaseHistory->c_of_c              = $purchase->c_of_c ?? 0;
        $purchaseHistory->other_costs         = $purchase->other_costs ?? 0;
        $purchaseHistory->grand_total         = $purchase->grand_total ?? 0;
        $purchaseHistory->notes               = $purchase->notes;
    
        $purchaseHistory->save();
    }

    /**
     * Handle the Purchase "updated" event.
     *
     * @param  use Modules\PurchaseManager\Entities\Purchase  $purchase
     * @return void
     */
    public function updated(Purchase $purchase)
    {
        //
    }

    /**
     * Handle the Purchase "deleting" event.
     *
     * @param  use Modules\PurchaseManager\Entities\Purchase  $purchase
     * @return void
     */
    public function deleting(Purchase $purchase)
    {
        $purchase->purchaseHistories()->delete();
        $purchase->orderHistories()->delete();
        $purchase->invoiceHistories()->delete();
    }

    /**
     * Handle the Purchase "deleted" event.
     *
     * @param  use Modules\PurchaseManager\Entities\Purchase  $purchase
     * @return void
     */
    public function deleted(Purchase $purchase)
    {
        //
    }

    /**
     * Handle the Purchase "forceDeleted" event.
     *
     * @param  use Modules\PurchaseManager\Entities\Purchase  $purchase
     * @return void
     */
    public function forceDeleted(Purchase $purchase)
    {
        //
    }

}

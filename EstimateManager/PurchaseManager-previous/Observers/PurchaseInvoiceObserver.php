<?php

namespace Modules\PurchaseManager\Observers;

use Modules\PurchaseManager\Entities\PurchaseInvoice;
use Modules\PurchaseManager\Entities\PurchaseInvoiceHistory;

class PurchaseInvoiceObserver {

    /**
     * Handle the PurchaseInvoice "created" event.
     *
     * @param  use Modules\PurchaseManager\Entities\PurchaseInvoice  $purchaseInvoice
     * @return void
     */
    public function saving(PurchaseInvoice $purchaseInvoice)
    {
    
        //
    }

    /**
     * Handle the PurchaseInvoice "created" event.
     *
     * @param  use Modules\PurchaseManager\Entities\PurchaseInvoice  $purchaseInvoice
     * @return void
     */
    public function saved(PurchaseInvoice $purchaseInvoice)
    {
        //
    }

    /**
     * Handle the PurchaseInvoice "creating" event.
     *
     * @param  use Modules\PurchaseManager\Entities\PurchaseInvoice  $purchaseInvoice
     * @return void
     */
    public function creating(PurchaseInvoice $purchaseInvoice)
    {
        //
    }

    /**
     * Handle the PurchaseInvoice "created" event.
     *
     * @param  use Modules\PurchaseManager\Entities\PurchaseInvoice  $purchaseInvoice
     * @return void
     */
    public function created(PurchaseInvoice $purchaseInvoice)
    {
        //
    }

    /**
     * Handle the PurchaseInvoice "updating" event.
     *
     * @param  use Modules\PurchaseManager\Entities\PurchaseInvoice  $purchaseInvoice
     * @return void
     */
    public function updating(PurchaseInvoice $purchaseInvoice)
    {
        $purchaseInvoiceHistory                             = new PurchaseInvoiceHistory();
        $purchaseInvoiceHistory->purchase_id                = $purchaseInvoice->purchase_id;
        $purchaseInvoiceHistory->approver_id                = $purchaseInvoice->approver_id;
        $purchaseInvoiceHistory->invoice_no                 = $purchaseInvoice->invoice_no;
        $purchaseInvoiceHistory->revision_no                = $purchaseInvoice->purchase->revision_no;
        $purchaseInvoiceHistory->invoice_amount             = $purchaseInvoice->invoice_amount;
        $purchaseInvoiceHistory->invoice_date               = $purchaseInvoice->invoice_date;
        $purchaseInvoiceHistory->save();
    }

    /**
     * Handle the PurchaseInvoice "updated" event.
     *
     * @param  use Modules\PurchaseManager\Entities\PurchaseInvoice  $purchaseInvoice
     * @return void
     */
    public function updated(PurchaseInvoice $purchaseInvoice)
    {
        //
    }

    /**
     * Handle the PurchaseInvoice "deleting" event.
     *
     * @param  use Modules\PurchaseManager\Entities\purchaseInvoice  $purchaseInvoice
     * @return void
     */
    public function deleting(PurchaseInvoice $purchaseInvoice)
    {
        //
    }

    /**
     * Handle the User "PurchaseInvoice" event.
     *
     * @param  use Modules\PurchaseManager\Entities\PurchaseInvoice  $purchaseInvoice
     * @return void
     */
    public function deleted(PurchaseInvoice $purchaseInvoice)
    {
        //
    }

    /**
     * Handle the PurchaseInvoice "forceDeleted" event.
     *
     * @param  use Modules\PurchaseManager\Entities\PurchaseInvoice  $purchaseInvoice
     * @return void
     */
    public function forceDeleted(PurchaseInvoice $purchaseInvoice)
    {
        //
    }

}

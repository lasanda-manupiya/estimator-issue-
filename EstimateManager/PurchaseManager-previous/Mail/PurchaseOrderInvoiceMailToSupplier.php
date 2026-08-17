<?php

namespace Modules\PurchaseManager\Mail;

use App\EmailTemplate;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\PurchaseManager\Entities\Purchase;

class PurchaseOrderInvoiceMailToSupplier extends Mailable {

    use Queueable,
        SerializesModels;

    protected $purchase;

    protected $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Purchase $purchase, array $data) {

        $this->purchase = $purchase;
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build() {
       
        $template = EmailTemplate::where('status', 1)->where('type', 'purchase_order_invoice_mail_to_supplier')->first();

        if ($template) {

            $subject = $template->subject;
            $body = $template->template;
            $body = str_replace('##NAME##',  $this->purchase->supplier->supplier_name, $body);
            $body = str_replace('##PURCHASE_ORDER_LINK##', route('purchase.orders.view', $this->purchase->id), $body);
            $purchase= $this->purchase;
            $data = $this->data;
            $pdf = \PDF::loadView('purchasemanager::purchase_orders.invoice', compact('purchase', 'data'));
            $attachmentName = $this->purchase->purchase_no.'_purchase_order.pdf';
            $this->subject($subject)
                    ->view('emails.email')
                    ->attachData($pdf->output(), $attachmentName, [
                        'mime' => 'application/pdf',
                    ])
                    ->with(['body' => $body]);
        }
    }

}

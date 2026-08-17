<?php

namespace Modules\PurchaseManager\Mail;

use App\User;
use App\EmailTemplate;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\PurchaseManager\Entities\Purchase;

class PurchaseOrderMailToSupplier extends Mailable {

    use Queueable,
        SerializesModels;

    protected $purchase;
    protected $user;

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
    public function __construct(Purchase $purchase, User $user) {

        $this->purchase = $purchase;
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build() {
       
        $template = EmailTemplate::where('status', 1)->where('type', 'purchase_order_mail_to_supplier')->first();

        if ($template) {

            $subject = $template->subject;
            $body = $template->template;
            $body = str_replace('##NAME##',  $this->user->supplier_name, $body);
            $body = str_replace('##PURCHASE_ORDER_LINK##', route('purchase.orders.view', $this->purchase->id), $body);

            $this->subject($subject)
                    ->view('emails.email')
                    ->with(['body' => $body]);
        }
    }

}

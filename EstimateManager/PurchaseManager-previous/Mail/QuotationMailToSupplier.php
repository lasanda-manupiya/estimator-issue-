<?php

namespace Modules\PurchaseManager\Mail;

use App\User;
use App\EmailTemplate;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\PurchaseManager\Entities\Quotation;

class QuotationMailToSupplier extends Mailable {

    use Queueable,
        SerializesModels;

    protected $quotation;
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
    public function __construct(Quotation $quotation, User $user) {

        $this->quotation = $quotation;
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build() {
       
        $template = EmailTemplate::where('status', 1)->where('type', 'quotation_mail_to_supplier')->first();

        if ($template) {

            $subject = $template->subject;
            $body = $template->template;
            $body = str_replace('##NAME##',  $this->user->supplier_name, $body);
            $body = str_replace('##QUOTATION_LINK##', route('quotations.supplier.quotation', $this->quotation->id), $body);

            $this->subject($subject)
                    ->view('emails.email')
                    ->with(['body' => $body]);
        }
    }

}

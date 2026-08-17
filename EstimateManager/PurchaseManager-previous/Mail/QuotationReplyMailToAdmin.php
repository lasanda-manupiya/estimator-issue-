<?php

namespace Modules\PurchaseManager\Mail;

use App\User;
use App\EmailTemplate;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\PurchaseManager\Entities\Quotation;

class QuotationReplyMailToAdmin extends Mailable {

    use Queueable,
        SerializesModels;

    protected $quotation;
    protected $company;

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
    public function __construct(Quotation $quotation, User $company) {

        $this->quotation = $quotation;
        $this->company = $company;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build() {
       
        $template = EmailTemplate::where('status', 1)->where('type', 'quotation_reply_mail_to_admin')->first();

        if ($template) {

            $subject = $template->subject;
            $body = $template->template;
            $body = str_replace('##NAME##',  $this->company->company_name, $body);
            $body = str_replace('##QUOTATION_LINK##', route('quotations.view', $this->quotation->id), $body);

            $this->subject($subject)
                    ->view('emails.email')
                    ->with(['body' => $body]);
        }
    }

}

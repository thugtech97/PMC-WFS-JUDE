<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NextApproverNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $receiver;
    public $transaction;
    // public $setting;

    /**
     * Create a new message instance.
     *
     * @param $setting
     * @param $user
     * @param $token
     */
    // public function __construct($user,$setting)
    public function __construct($receiver, $transaction)
    {
        $this->receiver = $receiver;
        $this->transaction = $transaction;
        // $this->setting = $setting;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mail.approver.next-approver')
            ->text('mail.approver.next-approver_plain')
            ->subject('OREM Approval Notification');
    }
}

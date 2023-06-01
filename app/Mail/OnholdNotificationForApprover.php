<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class OnholdNotificationForApprover extends Mailable
{
    use Queueable, SerializesModels;

    public $requestor;
    public $onholdby;

    /**
     * Create a new message instance.
     *
     * @param $setting
     * @param $user
     * @param $token
     */
    // public function __construct($user,$setting)
    public function __construct($requestor,$onholdby)
    {
        $this->requestor = $requestor;
        $this->onholdby = $onholdby;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mail.onhold.next-approver')
            ->text('mail.onhold.next-approver_plain')
            ->subject('OREM On Hold Notification');
    }
}

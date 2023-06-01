<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ApprovedNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $requestor;    

    /**
     * Create a new message instance.
     *
     * @param $setting
     * @param $user
     * @param $token
     */
    // public function __construct($user,$setting)
    public function __construct($requestor)
    {
        $this->requestor = $requestor;        
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mail.approved.next-approver')
            ->text('mail.approved.next-approver_plain')
            ->subject('OREM Approved Notification');
    }
}

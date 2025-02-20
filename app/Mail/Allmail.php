<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Allmail extends Mailable
{
    use Queueable, SerializesModels;
    public $data;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        if ($this->data['template'] == 'clubemail'){
            $template = 'emails.clubMail';
        } elseif ($this->data['template'] == 'forumQuestion') {
            $template = 'emails.forumQuestion';
        } elseif ($this->data['template'] == 'BlogPost') {
            $template = 'emails.blogPost';
        } elseif ($this->data['template'] == 'bookstatusmail') {
            $template = 'emails.bookstatusmail';
        }
        elseif ($this->data['template'] == 'test') {
            $template = 'emails.test';
        }
        // elseif($this->data['template'] == 'fileupload'){
        //     $template = 'mail.fileupload';
        // }elseif($this->data['template'] == 'SupportTicket'){
        //     $template = 'mail.supportTicket';
        // }elseif($this->data['template'] == 'supportTicketMessage'){
        //     $template = 'mail.supportTicketMessage';
        // }elseif($this->data['template'] == 'fileStatus'){
        //     $template = 'mail.fileStatus';
        // }elseif($this->data['template'] == 'fileSendAdmin'){
        //     $template = 'mail.fileSendAdmin';
        // }elseif($this->data['template'] == 'ContactUs'){
        //     $template = 'mail.contactUs';
        // }elseif($this->data['template'] == 'Dealer'){
        //     $template = 'mail.dealer';
        // }else{
        //     $template = 'mail.purchase_shop';
        // }

        return $this->subject($this->data['subject'] ?? config('app.name'))->view($template)->with('data',$this->data);

    }
}

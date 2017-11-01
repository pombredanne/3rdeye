<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class MyMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $title;
    public $percentage;
    public $wordcount;
    public $sentencecount;
    public $charactercount;
    public $matchingsentences;
    public $matchingsources;
    public $searchtype;
    public $uploadtime;
    public $pdflink;
    
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $title, $percentage, $wordcount, $sentencecount, $charactercount, $matchingsentences, $matchingsources, $searchtype, $uploadtime, $pdflink)
    {
        //
        $this->user = $user;
        $this->title = $title;
        $this->percentage = $percentage;
        $this->wordcount = $wordcount;
        $this->sentencecount = $sentencecount;
        $this->charactercount = $charactercount;
        $this->matchingsentences = $matchingsentences;
        $this->matchingsources = $matchingsources;
        $this->searchtype = $searchtype;
        $this->uploadtime = $uploadtime;
        $this->pdflink = $pdflink;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('no-reply@3rdeye.co')->view('email.emailreport');
    }
}

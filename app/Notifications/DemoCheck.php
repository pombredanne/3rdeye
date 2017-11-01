<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

use Illuminate\Support\Facades\DB;

class DemoCheck extends Notification
{
    use Queueable;

    public $mytext;
    
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($mytext)
    {
        //
        $this->mytext = $mytext;
    }
    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'broadcast', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    
   public function toArray($notifiable)
    {
        $text = $this->mytext;
        
           
        return [
            'search_type' => 'Text',
            'title' => 'Demo',
            'content' => $content, 
            'text' =>"", 
            'character_count' => $character_count, 
            'word_count' => $word_count, 
            'sentence_count' => $sentence_count, 
            'matching_sentences' => $matching_sentences, 
            'matching_sources' => $matching_sources, 
            'plagiarism_percentage' => $plagiarism_percentage, 
            'search_result' => $search_result , 
            'search_result_array' => $search_result_array
        ];
        
        /*return view('demo/admin', ['search_type' => 'Text', 'title' => 'Demo','content' => $content, 'text' =>"", 'character_count' => $character_count, 'word_count' => $word_count, 'sentence_count' => $sentence_count, 'matching_sentences' => $matching_sentences, 'matching_sources' => $matching_sources, 'plagiarism_percentage' => $plagiarism_percentage, 'search_result' => $search_result , 'search_result_array' => $search_result_array]);*/
    }
    
    /*  public function toBroadcast($notifiable)
    {
        return [
            //
        ];
    }*/
}

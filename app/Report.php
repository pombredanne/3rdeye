<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Report extends Model
{
    use Notifiable;
    //
    protected $fillable = [
        'user_id', 'search_type', 'search_mode', 'status', 'title', 'character_count', 'word_count', 'sentence_count', 'matching_sentences', 'matching_sources', 'plagiarism_percentage', 'content', 'search_result', 'pdf_report'
    ];
    
}

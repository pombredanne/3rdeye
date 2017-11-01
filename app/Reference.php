<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reference extends Model
{
    //
    
    protected $fillable = [
        'category', 'supervisor','title', 'link', 'author', 'institution',
    ];
    
}

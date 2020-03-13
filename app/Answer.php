<?php

namespace App;

use App\User;
use App\Question;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    // defining relationship among Answer and Question
    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    // defining relationship among Answer and User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getBodyHtmlAttribute()
    {
        //to transform from markup to html using accessor
        return \Parsedown::instance()->text($this->body);
    }
}

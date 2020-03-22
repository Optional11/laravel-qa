<?php

namespace App;

use App\User;
use App\Question;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{

    use VotableTrait;

    protected $fillable = ['body', 'user_id'];

    //added due to vuej js - so accessor will be vaaibale for vue component
    protected $appends = ['created_date', 'body_html'];

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
        //to transform from markup to html using accessor /clean = used Purifier package
        return clean(\Parsedown::instance()->text($this->body));
    }

    //accessor to get date
    public function getCreatedDateAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    public function getStatusAttribute()
    {
        return $this->isBest() ? 'vote-accepted' : '';
    }
    
    public function getIsBestAttribute()
    {
        return $this->isBest();
    }

    public function isBest()
    {
        return $this->id === $this->question->best_answer_id;
    }

    public static function boot()
    {
        parent::boot();

        static::created(function ($answer) {
            $answer->question->increment('answers_count');
        });
        
        static::deleted(function ($answer) {
            $answer->question->decrement('answers_count');
        });
    }

}

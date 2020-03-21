<?php

namespace App;

use App\User;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{

    use VotableTrait;
    // set massassigment fields
    protected $fillable = ['title', 'body'];

    //added due to vuej js - so accessor will be vaaibale for vue component
    protected $appends = ['created_date'];

    // defining relationship among user and Question
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // defining relationship among Answer and Question
    public function answers()
    {
        return $this->hasMany(Answer::class)->orderBy('votes_count', 'DESC');
    }

    //slug / we do not want set manually, so we need to set mutator // start with set and end with Atttribute
    //fun has to start with set
    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    //format Accessor - allows format eloquent attrbute when we retrieve it// start with get and end with Atttribute
    public function getUrlAttribute()
    {
        return route("questions.show", $this->slug);
    }

    public function getCreatedDateAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    public function getStatusAttribute()
    {
        if ($this->answers_count >0) {
            if($this->best_answer_id){
                return "answered-accepted";
            }
            return "answered";
        }
        return "unanswered";
    }

    public function getBodyHtmlAttribute()
    {
        //to transform from markup to html using accessor // using Purifier
        return clean($this->bodyHtml());
    }

    //to change best answer in db
    public function acceptBestAnswer(Answer $answer)
    {
        $this->best_answer_id = $answer->id;
        $this->save();
    }

    //many to many relation / second param because of we have our name of table, laravel consider
    //by default question_user
    public function favorites()
    {
        return $this->belongsToMany(User::class, 'favorites')->withTimestamps(); //, 'question_id', 'user_id');
    }

    public function isFavorited()
    {
        return $this->favorites()->where('user_id', auth()->id())->count() > 0;
    }

    public function getIsFavoritedAttribute()
    {
        return $this->isFavorited();
    }

    public function getFavoritesCountAttribute()
    {
        return $this->favorites->count();
    }

    public function getExcerptAttribute()
    {
        return $this->excerpt(250);
    }

    public function excerpt($length)
    {
        return Str::limit(strip_tags($this->bodyHtml()), $length);
    }

    private function bodyHtml()
    {
        return \Parsedown::instance()->text($this->body);
    }

}

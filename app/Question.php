<?php

namespace App;

use App\User;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    // set massassigment fields
    protected $fillable = ['title', 'body'];

    // defining relationship among user and Question
    public function user()
    {
        return $this->belongsTo(User::class);
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
        return route("questions.show", $this->id);
    }

    public function getCreatedDateAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    public function getStatusAttribute()
    {
        if ($this->answers >0) {
            if($this->best_answer_id){
                return "answered-accepted";
            }
            return "answered";
        }
        return "unanswered";
    }
}

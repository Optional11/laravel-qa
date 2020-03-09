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

    //slug / we do not want set manually, so we need to set mutator
    //fun has to start with set
    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }
}

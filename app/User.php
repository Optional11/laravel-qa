<?php

namespace App;

use App\Question;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // relationshit among User and questions
    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    //format Accessor - allows format eloquent attrbute when we retrieve it// start with get and end with Atttribute
    public function getUrlAttribute()
    {
        //return route("questions.show", $this->id);
        return '#';
    }
}

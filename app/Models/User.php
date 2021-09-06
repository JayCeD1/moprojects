<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get all of the questions for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function getUrlAttribute()
    {
//        return route('questions.show',$this->id);
        return '#';
    }

    public function answers():hasMany
    {
        return $this->hasMany(Answer::class);
    }

    public function getAvatarAttribute():string
    {
        $email = $this->email;
        $size = 5;
        return "https://www.gravatar.com/avatar/" . md5( strtolower( trim( $email ) ) ) . "&s=" . $size;
    }

    public function favorites()
    {
       return $this->belongsToMany(Question::class,'favorites')->withTimestamps();
    }

    public function voteQuestions()
    {
        return $this->morphedByMany(Question::class,'votable');
    }

    public function voteAnswers()
    {
        return $this->morphedByMany(Answer::class,'votable');
    }

    public function voteQuestion(Question $question,$vote)
    {
        $voteQuestion = $this->voteQuestions();
        $this->_vote($voteQuestion,$question,$vote);
    }

    public function voteAnswer(Answer $answer,$vote)
    {
        $voteAnswers = $this->voteAnswers();
        $this->_vote($voteAnswers,$answer,$vote);
    }

    private function _vote($relationship,$model,$vote)
    {
        if ($relationship->where('votable_id',$model->id)->exists()){
            $relationship->updateExistingPivot($model,['vote'=>$vote]);
        }else{
            $relationship->attach($model,['vote'=>$vote]);
        }
        $model->load('votes');//eager load after query
        $downVotes = (int) $model->downVotes()->sum('vote');//typecasting in play
        $upVotes = (int) $model->upVotes()->sum('vote');

        $model->votes_count = $downVotes + $upVotes;
        $model->save();
    }
}

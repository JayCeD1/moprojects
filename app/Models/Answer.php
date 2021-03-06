<?php

namespace App\Models;

use App\Traits\VotableTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Answer extends Model
{
    use HasFactory,VotableTrait;

    protected $fillable=['body','user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function getBodyHtmlAttribute()
    {
        return Str::markdown($this->body);
    }

    public function getCreatedDateAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    public function getStatusAttribute(): string
    {
        return $this->isBest() ? 'vote-accepted': '';
    }

    public static function boot()
    {
        parent::boot();
        static::created(function ($answer){
           $answer->question->increment('answers_count');
        });

        static::deleted(function ($answer){
            $answer->question->decrement('answers_count');
        });

    }

    public function getIsBestAttribute(): bool
    {
        return $this->isBest();
    }

    public function isBest(): bool
    {
        return $this->id === $this->question->best_answer_id;
    }


}

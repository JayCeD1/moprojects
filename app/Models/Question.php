<?php

namespace App\Models;

use App\Traits\VotableTrait;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Mail\Markdown;
use phpDocumentor\Reflection\Types\This;

class Question extends Model
{
    use HasFactory,VotableTrait;

    protected $fillable =['title','body'];

    /**
     * Get the user that owns the Question
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = $value;
        $this->attributes['slug'] = Str::slug($value);

    }

    public function getUrlAttribute()
    {
        return route('questions.show',$this->slug);
    }

    public function getCreatedDateAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    public function getStatusAttribute()
    {
        if ($this->answers_count > 0 ){
            if ($this->best_answer_id){
                return "answered-accepted";
            }
            return "answered";
        }
        return "unanswered";
//        $this->answers_count > 0 ? ($this->best_answer_id ? "answered-accepted": "answered") : "unanswered";
    }

    public function getBodyHtmlAttribute()
    {
        return Str::markdown($this->body);
    }

    public function answers()
    {
        return $this->hasMany(Answer::class);
    }

    public function acceptBestAnswer(Answer $answer)
    {
        $this->best_answer_id = $answer->id;
        $this->save();
    }

    public function favorites()
    {
        return $this->belongsToMany(User::class,'favorites')->withTimestamps();
    }

    public function isFavorited()
    {
        return $this->favorites()->where('user_id',auth()->id())->count() > 0;
    }

    public function getIsFavoritedAttribute()
    {
        return $this->isFavorited();
    }

    public function getFavoriteCountAttribute()
    {
        return $this->favorites->count();
    }


}

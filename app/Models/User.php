<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function votes(): HasMany
    {
        return $this->hasMany(Vote::class);
    }

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class, 'created_by');
    }

    public function like(Question $question): void
    {
        $this->votes()->updateOrCreate(
            ['question_id' => $question->id],
            [
                'like' => 1,
                'unlike' => 0,
            ]);
    }

    public function unlike(Question $question): void
    {
        $this->votes()->updateOrCreate(
            ['question_id' => $question->id],
            [
                'like' => 0,
                'unlike' => 1,
            ]);
    }
}

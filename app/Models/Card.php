<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Card extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'account_id',
        'name',
    ];

    public function scopeOfUser(Builder $query, User $user): Builder
    {
        return $query->whereHas('account', function ($query) use ($user) {
            $query->ofUser($user);
        });
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}

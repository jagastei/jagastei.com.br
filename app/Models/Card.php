<?php

namespace App\Models;

use Akaunting\Money\Money;
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
        'brand_id',
        'name',
        'limit',
        'digits',
        'digital',
        'credit',
        'international',
    ];

    protected $casts = [
        'digital' => 'boolean',
        'credit' => 'boolean',
        'international' => 'boolean',
    ];

    protected $appends = [
        'formatted_limit',
    ];

    public function getFormattedLimitAttribute()
    {
        return Money::BRL($this->limit ?? 0)->format();
    }

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

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
}

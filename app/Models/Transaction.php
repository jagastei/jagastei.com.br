<?php

namespace App\Models;

use Akaunting\Money\Money;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'title',
        'type',
        'value',
        'category_id',
        'account_id',
        'method',
        'card_id',
    ];

    protected $appends = [
        'formatted_value',
    ];

    public function getFormattedValueAttribute(): string
    {
        return Money::BRL($this->value ?? 0)->format();
    }

    public function scopeOfUser(Builder $query, User $user): Builder
    {
        return $query->whereHas('account', function ($query) use ($user) {
            $query->ofUser($user);
        });
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    public function card(): BelongsTo
    {
        return $this->belongsTo(Card::class);
    }
}

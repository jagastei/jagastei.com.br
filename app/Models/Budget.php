<?php

namespace App\Models;

use Akaunting\Money\Money;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Budget extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'name',
        'total',
        'current',
    ];

    protected $appends = [
        'formatted_total',
        'formatted_current',
        'formatted_diff',
        'percentage',
    ];

    public function getFormattedTotalAttribute(): string
    {
        return Money::BRL($this->total ?? 0)->format();
    }

    public function getFormattedCurrentAttribute(): string
    {
        return Money::BRL($this->current ?? 0)->format();
    }

    public function getFormattedDiffAttribute(): string
    {
        return Money::BRL($this->total - $this->current)->format();
    }

    public function getPercentageAttribute(): float
    {
        return ($this->current / $this->total) * 100;
    }

    public function scopeOfUser(Builder $query, User $user): Builder
    {
        return $query->where('user_id', $user->id);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

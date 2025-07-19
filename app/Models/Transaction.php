<?php

declare(strict_types=1);

namespace App\Models;

use Glhd\Bits\Database\HasSnowflakes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

final class Transaction extends Model
{
    use HasFactory;
    use HasSnowflakes;
    use Searchable;
    use SoftDeletes;

    public const METHODS = [
        'CASH',
        'CARD',
        'TED',
        'PIX',
        'OTHER',
        'UNKNOWN',
    ];

    protected $keyType = 'string';

    protected $fillable = [
        'title',
        'type',
        'value',
        'category_id',
        'account_id',
        'method',
        'card_id',
        'metadata',
        'datetime',
    ];

    protected $casts = [
        'metadata' => 'array',
        'datetime' => 'datetime',
    ];

    protected $appends = [
        'formatted_datetime',
    ];

    public function getFormattedDatetimeAttribute(): string
    {
        return $this->datetime->format('d/m/Y H:i');
    }

    public function getMetadata(?string $key = null): array|string|null
    {
        if ($key) {
            return $this->metadata[$key] ?? null;
        }

        return $this->metadata;
    }

    public function setMetadata(string $key, mixed $value): void
    {
        $this->metadata[$key] = $value;
    }

    public function scopeOfWallet(Builder $query, Wallet $wallet): Builder
    {
        return $query->whereHas('account', function ($query) use ($wallet) {
            $query->ofWallet($wallet);
        });
    }

    public function scopeOfAccount(Builder $query, Account $account): Builder
    {
        return $query->where('account_id', $account->id);
    }

    public function scopeOfCard(Builder $query, Card $card): Builder
    {
        return $query->where('card_id', $card->id);
    }

    public function scopeIn(Builder $query): Builder
    {
        return $query->where('type', 'IN');
    }

    public function scopeOut(Builder $query): Builder
    {
        return $query->where('type', 'OUT');
    }

    public function scopeOfCategory(Builder $query, Category $category): Builder
    {
        return $query->where('category_id', $category->id);
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

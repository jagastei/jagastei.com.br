<?php

namespace App\Models;

use Glhd\Bits\Database\HasSnowflakes;
use Glhd\Bits\Snowflake;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory;
    use HasSnowflakes;
    use SoftDeletes;

    public const IN_RECOMMENDED = [
        'Salário' => '#4CAF50',
        'Investimentos' => '#2196F3',
        'Freelance' => '#9C27B0',
    ];

    public const OUT_RECOMMENDED = [
        'Alimentação' => '#FF5252',
        'Transporte' => '#4CAF50',
        'Moradia' => '#2196F3',
        'Entretenimento' => '#FFC107',
        'Saúde' => '#E91E63',
        'Educação' => '#3F51B5',
        'Roupas' => '#009688',
        'Assinaturas' => '#F44336',
        'Pets' => '#8BC34A',
    ];

    protected $fillable = [
        'wallet_id',
        'name',
        'color',
        'icon',
        'emoji',
        'type',
        'budget',
    ];

    protected $casts = [
        'id' => Snowflake::class,
        'wallet_id' => Snowflake::class,
        'transactions_sum_value' => 'float',
    ];

    public function scopeOfWallet(Builder $query, Wallet $wallet): Builder
    {
        return $query->where('wallet_id', $wallet->id);
    }

    public function scopeIn(Builder $query): Builder
    {
        return $query->where('type', 'IN');
    }

    public function scopeOut(Builder $query): Builder
    {
        return $query->where('type', 'OUT');
    }

    public function wallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }
}

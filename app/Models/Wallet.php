<?php

namespace App\Models;

use Glhd\Bits\Database\HasSnowflakes;
use Glhd\Bits\Snowflake;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Wallet extends Model
{
    use HasFactory;
    use HasSnowflakes;
    use SoftDeletes;

    protected $keyType = 'string';

    public const CURRENCIES = [
        'BRL' => 'Real Brasileiro',
        'USD' => 'Dólar Americano',
        'EUR' => 'Euro',
        'GBP' => 'Libra Esterlina',
    ];

    protected $fillable = [
        'id',
        'user_id',
        'name',
        'personal',
        'currency',
    ];

    protected $casts = [
        'id' => Snowflake::class,
        'personal' => 'boolean',
    ];

    public function scopeOfUser(Builder $query, User $user): Builder
    {
        return $query->where('user_id', $user->id);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

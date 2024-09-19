<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Card extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'id',
        'card_security',
        'card_validation',
        'card_user_name',
        'card_short_name',
        'user_personal_identification',
        'user_id',
        'user_bank'
    ];
}

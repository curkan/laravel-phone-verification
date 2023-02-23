<?php

namespace Gogain\LaravelPhoneVerification\Models;

use Database\Factories\Phone\PhoneFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Phone extends Model
{
    use HasFactory;

    /** @return PhoneFactory */
    protected static function newFactory()
    {
        return PhoneFactory::new();
    }

    protected $fillable = [
        'user_id',
        'phone',
        'code',
        'status',
    ];

}

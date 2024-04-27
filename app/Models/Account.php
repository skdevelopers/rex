<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Account extends Model
{
    use HasFactory;

    public function transactions(): MorphMany
    {
        return $this->morphMany(TransactionDetail::class, 'entryable');
    }
}

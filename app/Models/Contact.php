<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @var \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\Customer> $customer
 */
class Contact extends Model
{
    /** @use HasFactory<\Database\Factories\ContactFactory> */
    use HasFactory;

    protected $fillable = [
        'first_name', 'last_name'
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}

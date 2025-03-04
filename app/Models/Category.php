<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @var \Illuminate\Database\Eloquent\Relations\HasMany<\App\Models\Customer> $customers
 */
class Category extends Model
{
    protected $fillable = ['name'];

    public function customers(): HasMany
    {
        return $this->hasMany(Customer::class)->chaperone();
    }
}

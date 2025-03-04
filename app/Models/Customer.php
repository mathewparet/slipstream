<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @var \Illuminate\Database\Eloquent\Relations\BelongsTo $category
 * @method \Illuminate\Database\Eloquent\Builder search($query)
 * @method \Illuminate\Database\Eloquent\Builder forCategory($category_id)
 */
class Customer extends Model
{
    /** @use HasFactory<\Database\Factories\CustomerFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'category_id',
        'reference',
        'start_date',
        'description',
    ];

    protected function casts()
    {
        return [
            'start_date' => 'date'
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function scopeSearch(Builder $query, $filter): Builder
    {
        $filter = '%'.strtolower($filter).'%';

        return $query->where('name', 'like', $filter)
                        ->orWhere('reference', 'like', $filter)
                        ->orWhere('description', 'like', $filter);
    }

    public function scopeForCategory(Builder $query, $category_id): Builder
    {
        return $query->where('category_id', $category_id);
    }
}

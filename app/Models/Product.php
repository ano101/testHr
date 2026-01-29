<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

class Product extends Model
{
    use HasFactory, Searchable, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'price',
        'category_id',
    ];

    public function toSearchableArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'price' => (float) $this->price,
            'category_id' => $this->category_id,
            'category_name' => $this->category?->name,
        ];
    }

    public function searchableAs(): string
    {
        return 'products';
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}

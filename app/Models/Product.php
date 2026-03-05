<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = ['title', 'slug', 'image', 'short_description', 'description', 'content', 'brand_id', 'category_id', 'subcategory_id', 'is_new'];

    protected $casts = [
        'is_new' => 'boolean',
    ];

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function subcategory(): BelongsTo
    {
        return $this->belongsTo(Subcategory::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function categories(): BelongsToMany
    {
        // Compatibility relation (0..1 category) through selected subcategory.
        return $this->belongsToMany(
            Category::class,
            'subcategories',
            'id',
            'category_id',
            'subcategory_id',
            'id',
        );
    }

    public function tabs(): HasMany
    {
        return $this->hasMany(ProductTab::class)->orderBy('sort_order');
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}

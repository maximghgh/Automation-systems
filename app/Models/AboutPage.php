<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AboutPage extends Model
{
    protected $fillable = [
        'id',
        'banner_title',
        'banner_text',
        'company_text',
        'advantages_items',
        'catalog_title',
        'catalog_text',
    ];

    protected $casts = [
        'advantages_items' => 'array',
    ];
}

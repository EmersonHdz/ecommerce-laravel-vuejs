<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Product extends Model
{
    use HasFactory;
    use HasSlug;
    use SoftDeletes;

    protected $fillable = ['title', 'description', 'price', 'quantity', 'published', 'created_by', 'updated_by'];
    
    /**
     * The `slug` field is used for SEO-friendly URLs.
     * It is generated from the `title` field.
     * The `slug` field is unique for each product.
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }
    
      /**
     * Defines `slug` as the route key for frontend URLs.
     * Example: /products/product-slug (SEO-friendly and human-readable).
     * @return string
     */
    public function getRouterKeyName()
    {
        return 'slug';
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('position');
    }

    public function getImageAttribute()
    {
        return $this->images->count() > 0 ? $this->images->get(0)->url : null;
    }
}

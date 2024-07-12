<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Product extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia;

    protected $fillable = [
        'category_id', 'subcategory_id', 'sub_subcategory_id', 'name', 'description', 'quantity', 'unit', 'unit_price',
    ];

    /**
     * Register media collections.
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('default');
    }

    /**
     * Get all the product's media files.
     */
    public function media(): MorphMany
    {
        return $this->morphMany(Media::class, 'model');
    }

    public function attributes(): HasMany
    {
        return $this->hasMany(Attribute::class);
    }

    public function stockTransactions(): HasMany
    {
        return $this->hasMany(StockTransaction::class);
    }

    public function invoiceItems(): HasMany
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function transactionDetails(): MorphMany
    {
        return $this->morphMany(TransactionDetail::class, 'entryable');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function subcategory(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'subcategory_id');
    }

    public function subSubCategory(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'sub_subcategory_id');
    }
}


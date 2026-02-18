<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CaseModel extends Model
{
    use HasFactory;

    protected $table = 'cases';

    protected $fillable = [
        'category_id',
        'title',
        'description',
        'solution',
        'image',
        'tags'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $with = ['category'];

    /**
     * Get the category that owns the CaseModel
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    //scope untuk search
    public function scopeSeacrh($query, $search)
    {
        return $query->when($search, function ($query, $search) {
            return $query->where('title', 'like', "%{search}%")
                ->orWhere('description', 'like', "%{search}%")
                ->orWhere('solution', 'like', "%{search}%")
                ->orWhere('tags', 'like', "%{search}%");
        });
    }

    //scope untuk filter
    public function scopeByCategory($query, $categoryId)
    {
        return $query->when($categoryId, function ($query, $categoryId) {
            return $query->where('category_id', $categoryId);
        });
    }
}

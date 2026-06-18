<?php

namespace App\Models\Library;

use App\Traits\AuditableTrait;
use App\Traits\SchoolScopeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Book extends Model
{
    use HasFactory, SchoolScopeTrait, AuditableTrait;

    protected $fillable = [
        'school_id', 'book_category_id', 'isbn', 'title', 'author',
        'publisher', 'edition', 'publication_year', 'language',
        'pages', 'description', 'quantity', 'available_quantity',
        'rack_number', 'shelf_number', 'price', 'status', 'barcode',
        'created_by', 'updated_by',
    ];

    protected $casts = [
        'publication_year' => 'integer',
        'pages' => 'integer',
        'quantity' => 'integer',
        'available_quantity' => 'integer',
        'price' => 'decimal:2',
        'status' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(BookCategory::class, 'book_category_id');
    }

    public function issues()
    {
        return $this->hasMany(BookIssue::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    public function scopeAvailable($query)
    {
        return $query->where('available_quantity', '>', 0);
    }

    public function scopeSearch($query, $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('title', 'like', "%{$term}%")->orWhere('author', 'like', "%{$term}%")->orWhere('isbn', 'like', "%{$term}%");
        });
    }
}

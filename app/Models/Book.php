<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Book extends Model
{
    /** @use HasFactory<\Database\Factories\BookFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'isbn',
        'description',
        'edition',
        'author_id',
        'total_copies',
        'available_copies',
        'price',
        'status',
    ];

    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class);
    }

    public function borrowings(): HasMany
    {
        return $this->hasMany(BorrowTransaction::class);
    }

    public function isAvailable(): bool
    {
        return $this->available_copies > 0;
    }

    //Decrease available copies when borrowed
    public function borrow(): void
    {
        if ($this->abailable_copies > 0) {
            $this->decrement('abailable_copies');
        }
    }

    //Increase available copies when return the book
    public function returnBook(): void
    {
        if ($this->abailable_copies < $this->total_copies) {
            $this->increment('abailable_copies');
        }
    }
}

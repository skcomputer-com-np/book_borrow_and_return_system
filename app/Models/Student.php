<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Student extends Model
{
    /** @use HasFactory<\Database\Factories\StudentFactory> */
    use HasFactory;

    protected $fillable = [
        'full_name',
        'roll_no',
        'email',
        'mobile',
        'department',
        'year_of_study',
        'status',
    ];

    public function borrowings(): HasMany
    {
        return $this->hasMany(BorrowTransaction::class);
    }


    /* ---------------- Scopes ---------------- */
    public function scopeBorrowed($query)
    {
        return $query->where('status', 'borrowed');
    }

    public function activeBorrowings(): HasMany
    {
        return $this->borrowings()->borrowed();
    }
}

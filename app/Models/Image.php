<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;

    public function scopeSortBy (Builder $query, string $column) {
        if ($column === 'name') return $query->orderBy('name');
        if ($column === 'new') return $query->latest();
        if ($column === 'old') return $query->oldest();
        return $query;
    }
}

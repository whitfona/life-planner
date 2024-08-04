<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Box extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'location'];
    protected $with = ['items'];

    public function items(): HasMany
    {
        return $this->hasMany(Item::class);
    }
}

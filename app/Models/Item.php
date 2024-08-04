<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Item extends Model
{
    use HasFactory;

    protected $fillable = ['description', 'photo_url', 'box_id'];

    public function box(): BelongsTo
    {
        return $this->belongsTo(Box::class);
    }
}

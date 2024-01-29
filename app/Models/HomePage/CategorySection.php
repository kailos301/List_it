<?php

namespace App\Models\HomePage;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategorySection extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function language()
    {
        return $this->belongsTo(Language::class, 'language_id', 'id');
    }
}

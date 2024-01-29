<?php

namespace App\Models\Prominence;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeatureSection extends Model
{
    use HasFactory;

    protected $fillable = ['language_id', 'subtitle', 'title', 'text'];
}

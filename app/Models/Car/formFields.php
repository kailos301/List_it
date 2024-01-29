<?php

namespace App\Models\Car;

use App\Models\Car;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class formFields extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = "form_fields";
    protected $primaryKey = "form_field_id";
    protected $fillable = ['label','type','category_field_id'];
}

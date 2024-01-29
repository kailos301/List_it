<?php

namespace App\Models\Car;

use App\Models\Car;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class formOptions extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = "form_select_data";
    protected $primaryKey = "form_select_id";
}

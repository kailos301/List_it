<?php

namespace App\Models\Car;

use App\Models\Car;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'language_id',
        'name',
        'image',
        'slug',
        'parent_id',
        'status',
        'serial_number',
    ];

    public function car_contents()
    {
        return $this->hasMany(CarContent::class);
    }
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }
    public function parentCat() {
        return $this->belongsTo(Category::class, 'parent_id');
    }
    public function childrenCat() {
        return $this->hasMany(Category::class, 'parent_id');
    }
    public function childArray($model = null)
    {
        $model = $model ?? $this;

        $result = [];

        if ($model !== $this) {
            array_push($result, $model->id);
        }

        $childs = $model->allChilds;
        if ($childs) {
            foreach ($childs as $value) {
                $result = array_merge($result, $this->childArray($value));
            }
        }

        return $result;
    }
}

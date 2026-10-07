<?php

namespace App\Models;

use App\Models\Concerns\HasImage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory, HasImage;

    protected $imageFolder = 'categories';
    protected $fillable = ['name', 'slug', 'image', 'icon'];
    //Relacion de uno a muchos
    public function subcategories()
    {
        return $this->hasMany(Subcategory::class);
    }
    public function products()
    {
        return $this->hasManyThrough(Product::class, Subcategory::class);
    }
}

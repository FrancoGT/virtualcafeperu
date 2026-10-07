<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\HasImage;

class Product extends Model
{
    use HasFactory, HasImage;

    protected $imageFolder = 'products';
    const BORRADOR = 1;
    const PUBLICADO = 2;
    protected $fillable = [
        'subcategory_id', 
        'name', 
        'slug', 
        'description', 
        'price', 
        'quantity'
    ];
    //Relacion uno a muchos inversa
    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class);
    }
    //relacion uno a muchos polimorfica
    public function images()
    {
        return $this->morphMany(Image::class, "imageable");
    }

    // Productos que se muestran en la tienda: el producto, su subcategoría y su categoría deben estar activos
    public function scopeVisible($query)
    {
        return $query->where('status', 1)
            ->whereHas('subcategory', fn ($sub) => $sub->visible());
    }

    public function getIsAvailableAttribute()
    {
        return (int) $this->quantity > 0;
    }
}

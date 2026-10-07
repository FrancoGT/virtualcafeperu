<?php

namespace App\Models;

use App\Models\Concerns\HasImage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subcategory extends Model
{
    use HasFactory, HasImage;

    protected $imageFolder = 'subcategories';
    protected $guarded = ['id', 'created_at', 'updated_at'];

    // La ruta del archivo se guarda en la columna "image" (p. ej. subcategories/abc123.png);
    // si está vacía se usa subcategories/{id}.png
    public function localImagePath(): string
    {
        $image = trim((string) $this->image);

        return $image !== '' ? $image : $this->imageFolder . '/' . $this->id . '.png';
    }

    public function rememberLocalImagePath(?string $path): void
    {
        $this->image = $path ?? ' '; // la columna no admite null
    }
    //Relacion uno a muchos
    public function products()
    {
        return $this->hasMany(Product::class);
    }
    // Subcategorías activas cuya categoría también está activa
    public function scopeVisible($query)
    {
        return $query->where('status', 1)
            ->whereHas('category', fn ($category) => $category->where('status', 1));
    }

    //Relacion uno a muchos inversa
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}

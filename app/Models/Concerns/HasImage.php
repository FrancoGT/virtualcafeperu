<?php

namespace App\Models\Concerns;

use Illuminate\Support\Facades\Storage;

/**
 * Imagen de un modelo: archivo físico en storage/{folder}/{id}.png o, si no existe,
 * la URL externa guardada en external_image_url.
 *
 * El modelo define: protected $imageFolder = 'products';
 */
trait HasImage
{
    // El modelo puede redefinirlo si guarda la ruta en una columna (ver Subcategory)
    public function localImagePath(): string
    {
        return $this->imageFolder . '/' . $this->id . '.png';
    }

    // Se llama al guardar (ruta) o borrar (null) el archivo; por defecto no hace nada
    public function rememberLocalImagePath(?string $path): void
    {
    }

    public function hasLocalImage(): bool
    {
        return $this->exists && Storage::disk('public')->exists($this->localImagePath());
    }

    public function deleteLocalImage(): void
    {
        if ($this->hasLocalImage()) {
            Storage::disk('public')->delete($this->localImagePath());
        }

        $this->rememberLocalImagePath(null);
    }

    // URL lista para <img src>: archivo físico (con ?v= para evitar caché), URL externa o null
    public function getImageUrlAttribute()
    {
        if ($this->hasLocalImage()) {
            $path = $this->localImagePath();

            return asset('storage/' . $path) . '?v=' . Storage::disk('public')->lastModified($path);
        }

        return $this->external_image_url ?: null;
    }
}

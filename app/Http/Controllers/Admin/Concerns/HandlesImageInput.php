<?php

namespace App\Http\Controllers\Admin\Concerns;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

/**
 * Campo de imagen de los formularios del admin (componente x-admin.image-input):
 * - image_source=file → archivo subido en "image", se guarda como storage/{folder}/{id}.png
 * - image_source=url  → URL externa en "external_image_url" (se usa sólo si no hay archivo físico,
 *   por eso al elegirla se borra el archivo anterior)
 *
 * El modelo debe usar App\Models\Concerns\HasImage.
 */
trait HandlesImageInput
{
    protected function usesExternalImage(Request $request): bool
    {
        return $request->input('image_source') === 'url';
    }

    // $required: el registro debe quedar con imagen (al crear un producto, por ejemplo)
    protected function imageRules(Request $request, bool $required): array
    {
        $url = $this->usesExternalImage($request);

        return [
            'image_source' => 'nullable|in:file,url',
            'image' => $url
                ? 'nullable'
                : [$required ? 'required' : 'nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'external_image_url' => $url
                ? ['bail', 'required', 'url', 'regex:/^https?:\/\//i', 'max:2048']
                : 'nullable',
        ];
    }

    protected function imageAttributes(): array
    {
        return ['image' => 'imagen', 'external_image_url' => 'URL de la imagen'];
    }

    protected function saveImageInput(Request $request, Model $model): void
    {
        if ($this->usesExternalImage($request)) {
            $model->deleteLocalImage();
            $model->external_image_url = $request->input('external_image_url');
            $model->save();

            return;
        }

        if ($request->hasFile('image')) {
            // Guardar siempre en formato .png
            $path = $model->localImagePath();
            Image::make($request->file('image')->getRealPath())->encode('png')->save(Storage::disk('public')->path($path));

            $model->rememberLocalImagePath($path);
            $model->external_image_url = null;
            $model->save();
        }
    }
}

{{--
    Selector de imagen con vista previa: subir un archivo o usar una URL externa.
    Envía image_source (file|url), image (archivo) y external_image_url; lo procesa
    App\Http\Controllers\Admin\Concerns\HandlesImageInput. El campo del modo no elegido
    se deshabilita para que no se envíe.

    $model: el producto/categoría que se edita (usa App\Models\Concerns\HasImage), o null al crear.
--}}
@props(['name' => 'image', 'id' => 'image', 'model' => null, 'required' => false])

@php
    $externalUrl = old('external_image_url', optional($model)->external_image_url);
    $hasLocal = $model && $model->hasLocalImage();
    $source = old('image_source', $externalUrl && !$hasLocal ? 'url' : 'file');
    $current = optional($model)->image_url;
    $urlError = $errors->first('external_image_url');
@endphp

<div class="flex flex-col gap-4 sm:flex-row sm:items-start"
    x-data="{
        source: @js($source),
        current: @js($current),
        filePreview: null,
        url: @js($externalUrl ?? ''),
        broken: false,
        get urlPreview() { return /^https?:\/\/.+/i.test(this.url.trim()) ? this.url.trim() : null },
        get preview() {
            if (this.source === 'url') return this.urlPreview;
            return this.filePreview || (@js($hasLocal) ? this.current : null);
        },
    }"
    x-effect="preview; broken = false">

    <div class="relative flex h-28 w-28 flex-shrink-0 items-center justify-center overflow-hidden rounded-xl bg-slate-100 ring-1 ring-inset ring-slate-200">
        <template x-if="preview && !broken">
            <img x-bind:src="preview" alt="Vista previa" class="h-full w-full object-cover" x-on:error="broken = true">
        </template>
        <template x-if="!preview">
            <i class="fa-regular fa-image text-3xl text-slate-300" aria-hidden="true"></i>
        </template>
        <template x-if="preview && broken">
            <span class="flex flex-col items-center gap-1 px-2 text-center text-[11px] font-semibold leading-tight text-red-600">
                <i class="fa-solid fa-link-slash text-lg" aria-hidden="true"></i>
                No se pudo cargar
            </span>
        </template>
    </div>

    <div class="min-w-0 flex-1">
        <input type="hidden" name="image_source" x-bind:value="source">

        {{-- Selector de modo --}}
        <div class="mb-3 inline-flex rounded-lg bg-slate-100 p-1 text-sm font-semibold" role="tablist" aria-label="Origen de la imagen">
            <button type="button" role="tab" x-on:click="source = 'file'" x-bind:aria-selected="source === 'file'"
                x-bind:class="source === 'file' ? 'bg-white text-ink shadow-sm' : 'text-ink-muted hover:text-ink'"
                class="flex items-center gap-2 rounded-md px-3 py-1.5 transition">
                <i class="fa-solid fa-upload text-xs" aria-hidden="true"></i> Subir archivo
            </button>
            <button type="button" role="tab" x-on:click="source = 'url'" x-bind:aria-selected="source === 'url'"
                x-bind:class="source === 'url' ? 'bg-white text-ink shadow-sm' : 'text-ink-muted hover:text-ink'"
                class="flex items-center gap-2 rounded-md px-3 py-1.5 transition">
                <i class="fa-solid fa-link text-xs" aria-hidden="true"></i> Usar URL externa
            </button>
        </div>

        {{-- Archivo --}}
        <div x-show="source === 'file'">
            <input type="file" name="{{ $name }}" id="{{ $id }}" accept="image/png,image/jpeg,image/gif,image/svg+xml"
                x-bind:disabled="source !== 'file'"
                @if ($required) x-bind:required="source === 'file' && !@js($hasLocal)" @endif
                x-on:change="const f = $event.target.files[0]; filePreview = f ? URL.createObjectURL(f) : null"
                class="block w-full cursor-pointer text-sm text-ink-muted file:mr-3 file:cursor-pointer file:rounded-lg file:border-0 file:bg-ink file:px-4 file:py-2 file:text-sm file:font-bold file:text-white hover:file:bg-ink-light">
            <p class="form-hint">
                PNG, JPG, GIF o SVG · máx. 2 MB.
                @if ($hasLocal)
                    <span x-show="!filePreview">Se muestra la imagen actual; elige otra sólo si quieres reemplazarla.</span>
                @elseif ($externalUrl)
                    <span x-show="!filePreview">Si subes un archivo, reemplazará a la URL externa.</span>
                @endif
            </p>
        </div>

        {{-- URL externa --}}
        <div x-show="source === 'url'" x-cloak>
            <label for="{{ $id }}_url" class="sr-only">URL de la imagen</label>
            <input type="url" name="external_image_url" id="{{ $id }}_url" x-model="url"
                x-bind:disabled="source !== 'url'" x-bind:required="source === 'url'"
                placeholder="https://ejemplo.com/imagen.jpg" autocomplete="off" inputmode="url"
                class="form-input @if ($urlError) border-red-400 @endif">
            @if ($urlError)
                <p class="form-error">{{ $urlError }}</p>
            @else
                <p class="form-hint">
                    Enlace directo a la imagen (debe empezar por http:// o https://).
                    @if ($hasLocal)
                        Al guardar se quitará la imagen subida actual.
                    @endif
                </p>
            @endif
        </div>
    </div>
</div>

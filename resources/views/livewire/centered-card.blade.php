@if ($isVisible)
<div class="flex justify-center items-center h-screen">
    <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        <div class="mb-4">
            <h2 class="text-xl font-bold">{{ $title }}</h2>
        </div>
        <div>
            <p>{{ $content }}</p>
        </div>
    </div>
</div>
@endif

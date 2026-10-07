<div>
    @if ($products->isNotEmpty())
        <section class="container-site py-12" aria-labelledby="popular-title">
            <div class="mb-6">
                <p class="eyebrow">Favoritos de la casa</p>
                <h2 id="popular-title" class="section-title mt-1">Lo más pedido</h2>
            </div>

            <div class="grid gap-3 md:grid-cols-2 lg:grid-cols-3">
                @foreach ($products as $product)
                    <x-product-card :product="$product" show-subcategory />
                @endforeach
            </div>
        </section>
    @endif
</div>

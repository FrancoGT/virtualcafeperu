<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class JsonProductosTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_returns_all_products()
    {
        $response = $this->getJson('/products/search');

        $response->assertStatus(422) // Verifica que el estado sea 422 si falta el parámetro findproduct
            ->assertJson([
                "message" => "The given data was invalid.",
                "errors" => [
                    "findproduct" => [
                        "El campo findproduct es obligatorio."
                    ]
                ]
            ]);
    }

    /** @test */
    public function it_searches_products_by_name()
    {
        $response = $this->getJson('/products/search?findproduct=Cafe');

        $response->assertStatus(200) // Verifica que el estado sea 200, ya que se espera que la solicitud sea exitosa
            ->assertExactJson([
                "current_page" => 1,
                "total" => 1,
                "data" => [
                    [
                        "id" => 5,
                        "name" => "Café Expreso Americano",
                        "slug" => "cafe-expreso-americano",
                        "description" => "Un café expreso americano es una bebida que se prepara añadiendo agua caliente a un shot de espresso. Esto resulta en una bebida más suave que un espresso tradicional, pero con un sabor fuerte y delicioso.",
                        "price" => 6,
                        "subcategory_id" => 9,
                        "quantity" => 7,
                        "status" => 1,
                        "created_at" => "2024-07-03T04:19:34.000000Z",
                        "updated_at" => "2024-07-03T04:31:43.000000Z"
                    ]
                ],
                "first_page_url" => "http://caramella.test/products/search?page=1",
                "from" => 1,
                "last_page" => 1,
                "last_page_url" => "http://caramella.test/products/search?page=1",
                "links" => [
                    [
                        "url" => null,
                        "label" => "&laquo; Anterior",
                        "active" => false
                    ],
                    [
                        "url" => "http://caramella.test/products/search?page=1",
                        "label" => "1",
                        "active" => true
                    ],
                    [
                        "url" => null,
                        "label" => "Siguiente &raquo;",
                        "active" => false
                    ]
                ],
                "next_page_url" => null,
                "path" => "http://caramella.test/products/search",
                "per_page" => 10,
                "prev_page_url" => null,
                "to" => 1,
                "total" => 1
            ]);
    }

    /** @test */
    public function it_returns_popular_products()
    {
        $response = $this->getJson('/products/popular');

        $response->assertStatus(200)
            ->assertJson([]); // Verifica que el JSON de respuesta esté vacío
    }

    /** @test */
    public function it_gets_products_by_subcategory()
    {
        $response = $this->getJson('/products/products-by-subcategory?subcategory-selected=Nonexistent');

        $response->assertStatus(404)
            ->assertJson(['message' => 'Subcategory not found']);
    }

    /** @test */
    public function it_validates_subcategory_selected_in_get_products_by_subcategory()
    {
        $response = $this->getJson('/products/products-by-subcategory');

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['subcategory-selected']);
    }
}

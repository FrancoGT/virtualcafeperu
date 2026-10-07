<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * URL de imagen externa: se usa cuando el producto/categoría no tiene archivo
 * físico en storage/{products|categories}/{id}.png.
 */
class AddExternalImageUrlToProductsAndCategories extends Migration
{
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('external_image_url', 2048)->nullable()->after('quantity');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->string('external_image_url', 2048)->nullable()->after('icon');
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('external_image_url');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('external_image_url');
        });
    }
}

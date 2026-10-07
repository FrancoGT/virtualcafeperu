<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * URL de imagen externa de la subcategoría: se usa cuando no hay archivo físico
 * en la ruta guardada en subcategories.image.
 */
class AddExternalImageUrlToSubcategories extends Migration
{
    public function up()
    {
        Schema::table('subcategories', function (Blueprint $table) {
            $table->string('external_image_url', 2048)->nullable()->after('image');
        });
    }

    public function down()
    {
        Schema::table('subcategories', function (Blueprint $table) {
            $table->dropColumn('external_image_url');
        });
    }
}

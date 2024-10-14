<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('awarded', function (Blueprint $table) {
            $table->increments("awd_id");
            $table->integer("awd_doc")->unsigned();
            $table->integer("awd_was_awd")->default(0);
            $table->timestamp("awarded_at")->nullable(true);
            $table->timestamp("created_at")->useCurrent();
            $table->timestamp("updated_at")->useCurrent();
        });
        Schema::table('awarded', function (Blueprint $table) {
            $table->foreign("awd_doc")->references("form_id")->on("forms");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('awarded');
    }
};

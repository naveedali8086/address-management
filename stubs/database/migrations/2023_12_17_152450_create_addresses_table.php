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
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->string('line_1');
            $table->string('line_2')->nullable();
            $table->string('postal_code')->nullable();
            $table->foreignId('country_id')->constrained('countries');
            $table->foreignId('region_id')->constrained('regions'); // a state or a province
            $table->foreignId('city_id')->constrained('cities');
            $table->string('landmark')->nullable();
            $table->string('lat')->nullable();
            $table->string('long')->nullable();
            $table->unsignedBigInteger('addressable_id');
            $table->string('addressable_type');
            $table->string('belongs_to');
            $table->unsignedTinyInteger('applicable_for_shipping')->nullable()->default(0);
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};

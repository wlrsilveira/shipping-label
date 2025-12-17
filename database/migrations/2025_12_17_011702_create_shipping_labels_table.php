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
        Schema::create('shipping_labels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->json('from_address');
            $table->json('to_address');
            $table->json('package_data');
            $table->string('external_shipment_id')->nullable();
            $table->string('provider')->nullable();
            $table->text('label_url')->nullable();
            $table->string('tracking_code')->nullable();
            $table->string('status')->default('pending');
            $table->string('carrier')->nullable();
            $table->string('service')->nullable();
            $table->decimal('rate', 10, 2)->nullable();
            $table->timestamps();

            $table->index('user_id');
            $table->index('external_shipment_id');
            $table->index(['provider', 'external_shipment_id']);
            $table->index('tracking_code');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipping_labels');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\OIPRequestStatus;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('oip_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->string('business_type');
            $table->string('organization_code');
            $table->string('status')->default(OIPRequestStatus::PENDING->value);
            $table->timestamp('expire_at')->default(now()->addMonths(12));
            $table->string('origin')->nullable();
            $table->string('destination')->nullable();
            $table->string('mode')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('oip_requests');
    }
};

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
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('branch_id');
            $table->unsignedBigInteger('department_id');
            $table->string('business_type');
            $table->string('organization_code');
            $table->string('status')->default(OIPRequestStatus::PENDING->value);
            $table->timestamp('expire_at')->default(now()->addMonths(12));
            $table->mediumText('comment')->nullable();
            $table->string('origin')->nullable();
            $table->string('destination')->nullable();
            $table->string('mode')->nullable();
            $table->string('shipment_number')->nullable();
            $table->string('value_add')->nullable();
            $table->mediumText('reject_reason')->nullable();
            $table->unsignedBigInteger('status_updated_by')->nullable();
            $table->timestamp('status_updated_at')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade');
            $table->foreign('status_updated_by')->references('id')->on('users')->onDelete('cascade');
            
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

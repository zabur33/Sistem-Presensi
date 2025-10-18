<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('overtime_requests', function (Blueprint $table) {
            $table->string('face_photo_path')->nullable()->after('reason');
            $table->string('support_photo_path')->nullable()->after('face_photo_path');
            $table->string('address')->nullable()->after('support_photo_path');
        });
    }

    public function down(): void
    {
        Schema::table('overtime_requests', function (Blueprint $table) {
            $table->dropColumn(['face_photo_path','support_photo_path','address']);
        });
    }
};

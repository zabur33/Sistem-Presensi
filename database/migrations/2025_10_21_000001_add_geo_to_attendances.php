<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->decimal('lat', 10, 7)->nullable()->after('location_text');
            $table->decimal('lng', 10, 7)->nullable()->after('lat');
            $table->float('accuracy')->nullable()->after('lng'); // meters
        });
    }

    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropColumn(['lat', 'lng', 'accuracy']);
            // keep time_out as it's used elsewhere
        });
    }
};

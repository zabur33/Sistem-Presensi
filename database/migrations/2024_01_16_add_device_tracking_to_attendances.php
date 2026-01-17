<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->string('device_id', 100)->nullable()->after('user_id');
            $table->string('device_fingerprint', 255)->nullable()->after('device_id');
            $table->timestamp('last_activity_at')->nullable()->after('updated_at');
            $table->index(['user_id', 'date', 'device_id']);
        });
    }

    public function down()
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'date', 'device_id']);
            $table->dropColumn(['device_id', 'device_fingerprint', 'last_activity_at']);
        });
    }
};

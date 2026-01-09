<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Check if monitor_id column already exists
        if (Schema::hasColumn('monitors', 'monitor_id')) {
            return; // Migration already applied
        }

        $driverName = DB::getDriverName();

        Schema::table('monitors', function (Blueprint $table) {
            // Add monitor_id column to store UptimeRobot API ID
            $table->bigInteger('monitor_id')->nullable()->unique()->after('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('monitors', 'monitor_id')) {
            Schema::table('monitors', function (Blueprint $table) {
                $table->dropUnique(['monitor_id']);
                $table->dropColumn('monitor_id');
            });
        }
    }
};

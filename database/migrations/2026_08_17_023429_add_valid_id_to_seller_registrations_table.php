<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('seller_registrations', function (Blueprint $table) {
            $table->string('valid_id_path')->nullable()->after('business_permit');
        });
    }

    public function down()
    {
        Schema::table('seller_registrations', function (Blueprint $table) {
            $table->dropColumn('valid_id_path');
        });
    }
};

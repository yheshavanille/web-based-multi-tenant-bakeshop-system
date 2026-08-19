<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('seller_registrations', function (Blueprint $table) {
            $table->boolean('is_18_above')->default(false);
            $table->boolean('has_valid_id')->default(false);
            $table->boolean('has_business_permit')->default(false);
            $table->boolean('has_complete_contact')->default(false);
        });
    }

    public function down()
    {
        Schema::table('seller_registrations', function (Blueprint $table) {
            $table->dropColumn(['is_18_above', 'has_valid_id', 'has_business_permit', 'has_complete_contact']);
        });
    }
};

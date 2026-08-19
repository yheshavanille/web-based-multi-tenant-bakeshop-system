<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('seller_registrations', function (Blueprint $table) {
            $table->boolean('has_valid_documents')->default(false)->after('has_complete_contact');
        });
    }

    public function down()
    {
        Schema::table('seller_registrations', function (Blueprint $table) {
            $table->dropColumn('has_valid_documents');
        });
    }
};

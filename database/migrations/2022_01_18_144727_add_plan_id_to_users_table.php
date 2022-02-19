<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPlanIdToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('plan_id')->after('email')->nullable()->constrained('plans','id')->onDelete('restrict');
            $table->string('first_name')->after('email');
            $table->string('last_name')->after('first_name');
            $table->string('phone')->after('last_name')->nullable();
            $table->string('avatar')->after('phone')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['plan_id','first_name','last_name','phone','avatar']);
        });
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('ref');
            $table->string('name');
            $table->string('company_name')->nullable();
            $table->string('type')->nullable();
            $table->string('phone1')->nullable();
            $table->string('phone2')->nullable();
            $table->string('mobile')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->string('register_no1')->nullable();
            $table->string('register_no2')->nullable();
            $table->text('address')->nullable();
            $table->text('custom_fields')->nullable();
            $table->foreignId('company_id')->nullable()->constrained('companies','id')->onDelete('restrict');
            $table->foreignId('branch_id')->nullable()->constrained('branches','id')->onDelete('restrict');
            $table->foreignId('country_id')->nullable()->constrained('countries','id')->onDelete('set null');
            $table->foreignId('state_id')->nullable()->constrained('states','id')->onDelete('set null');
            $table->foreignId('currency_id')->nullable()->constrained('currencies','id')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clients');
    }
}

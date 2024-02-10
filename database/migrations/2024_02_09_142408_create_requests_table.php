<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('request', function (Blueprint $table) {
            $table->id();
            $table->integer('customer_id')->default(false);
            $table->bigInteger('contact_no')->default(false);
            $table->string('client_name', 255)->nullable(false);
            $table->string('electrician_name', 255)->nullable(false);
            $table->bigInteger('electrician_no')->default(false);
            $table->string('point_con_name', 255)->nullable();
            $table->bigInteger('point_con_no')->default(0);
            $table->integer('req_per_type')->default(0);
            $table->string('req_type', 255)->nullable();
            $table->string('power_type', 255)->nullable();
            $table->integer('home_no')->default(0);
            $table->string('address_line_1', 255)->nullable();
            $table->string('address_line_2', 255)->nullable();
            $table->string('area', 255)->nullable();
            $table->integer('city_id')->default(0);
            $table->integer('state_id')->default(0);
            $table->integer('pincode')->default(0);
            $table->string('quotation_pdf')->default('');
            $table->string('send_to_customer', 255)->nullable();
            $table->dateTime('created_at')->nullable();
            $table->integer('entryby')->default(0);
            $table->string('entryip', 20)->default('');
            $table->dateTime('updated_at')->nullable();
            $table->integer('updateby')->default(0);
            $table->string('updateip', 20)->default('');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('requests');
    }
}

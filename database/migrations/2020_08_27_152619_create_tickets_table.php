<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('officer_id');
            $table->timestamp('issued_at');
            $table->string('reg_no', 16);
            $table->binary('front_image');
            $table->binary('rear_image');
            $table->binary('dash_image');
            $table->binary('location_image');
            $table->decimal('gps_lat', 10, 7);
            $table->decimal('gps_lon', 10, 7);
            $table->boolean('dvla_req_sent')->default(0);
            $table->string('first_name', 20)->nullable();
            $table->string('last_name', 20)->nullable();
            $table->string('address1', 20)->nullable();
            $table->string('address2', 20)->nullable();
            $table->string('address3', 20)->nullable();
            $table->string('town', 20)->nullable();
            $table->string('postcode', 20)->nullable();
            $table->boolean('notice_sent')->default(0);
            $table->boolean('reminder_sent')->default(0);
            $table->string('client_access_code', 20)->nullable();
            $table->decimal('payment_made_amt', 3, 2)->nullable();
            $table->date('payment_made_date')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tickets');
    }
}

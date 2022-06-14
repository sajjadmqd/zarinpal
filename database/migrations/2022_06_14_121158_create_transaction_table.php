<?php

use Sajjadmgd\Zarinpal\Helpers;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaction', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('amount');
            $table->string('description');
            $table->string('authority');
            $table->string('ref_id');
            $table->string('payable_type');
            $table->unsignedBigInteger('payable_id');
            $table->enum('status', Helpers::TransctionStatuses);
            $table->timestamp('payed_at');
            $table->timestamp('expire_in');
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
        Schema::dropIfExists('transaction');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->integer('total');
            $table->timestamps();
        });

        Schema::create('purchase_detail', function (Blueprint $table) {
            $table->id();
            $table->integer(
                'id_purchase'
            );
            $table->integer(
                'id_product'
            );
            $table->integer('amount');
            $table->integer('price');
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
        Schema::dropIfExists(
            'purchases'
        );
        Schema::dropIfExists('purchase_detail');
    }
}

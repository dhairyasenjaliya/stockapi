<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stocks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('company_name');
            $table->enum('exchange', ['bse', 'nse','both']); 
            $table->integer('sector')->unsigned();
            $table->string('1_Year_Price');
            $table->string('1_Year_Change');
            $table->string('9_Month_Price');
            $table->string('9_Month_Change');
            $table->string('6_Month_Price');
            $table->string('6_Month_Change');
            $table->string('3_Month_Price');
            $table->string('3_Month_Change');
            $table->string('1_Month_Price');
            $table->string('1_Month_Change');
            $table->string('2_Week_Price');
            $table->string('2_Week_Change');
            $table->string('1_Week_Price'); 
            $table->string('1_Week_Change'); 
            $table->string('price');
            $table->integer('fav_counter')->default('0');
            $table->timestamps();
            $table->foreign('sector')
            ->references('id')->on('sectors')
            ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stocks');
    }
}

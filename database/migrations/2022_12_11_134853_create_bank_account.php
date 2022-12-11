<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('bank_account');
        Schema::create('bank_account', function (Blueprint $table) {
            $table->id();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->double('balance')->default(0);
            $table->integer('user_id');
            $table->integer('accNumber')->unique();
            $table->string('accName');
            $table->string('currency');
        });
        DB::table('bank_account')->insert([
            ['balance' => 1000, 'user_id' => 1, 'accNumber' =>0000000001,  'accName' => 'Główne', 'currency' => 'PLN'],
            ['balance' => 2000, 'user_id' => 2, 'accNumber' =>0000000002,  'accName' => 'Oszczędnościowe', 'currency' => 'USD'],
            ['balance' => 3000, 'user_id' => 3, 'accNumber' =>0000000003,  'accName' => 'Walutowe', 'currency' => 'EURO'],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bank_account');
    }
};

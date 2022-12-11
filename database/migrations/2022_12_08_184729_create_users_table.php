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
        Schema::create('custom_users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('token')->default('');;
        });

        DB::table('custom_users')->insert([
            ['name' => 'user1', 'first_name' => 'Franek', 'last_name' =>'kowalski',  'password' => bcrypt('p@ssword1'), 'email' => 'user1@example.com'],
            ['name' => 'user2','first_name' => 'Franek', 'last_name' =>'kowalski', 'password' => bcrypt('p@ssword2'), 'email' => 'user2@example.com'],
            ['name' => 'user3','first_name' => 'Franek', 'last_name' =>'kowalski', 'password' => bcrypt('p@ssword3'), 'email' => 'user3@example.com'],
            ['name' => 'user4','first_name' => 'Franek', 'last_name' =>'kowalski', 'password' => bcrypt('p@ssword4'), 'email' => 'user4@example.com'],
            ['name' => 'user5','first_name' => 'Franek', 'last_name' =>'kowalski', 'password' => bcrypt('p@ssword5'), 'email' => 'user5@example.com'],
        ]);
    }

    

    /**
     * Reverse the] migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('custom_users');
    }
};

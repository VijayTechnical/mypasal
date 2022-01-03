<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('custom_fields', function (Blueprint $table) {
            $table->id();
            $table->enum('type',['textarea','text','options']);
            $table->string('placeholder')->nullable();
            $table->string('title')->nullable();
            $table->tinyInteger('is_required')->default(0);
            $table->tinyInteger('status')->default(0);
            $table->text('values')->nullable();
            $table->tinyInteger('highlight')->default(0);
            $table->timestamp('deleted_at')->nullable();
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
        Schema::dropIfExists('custom_fields');
    }
}

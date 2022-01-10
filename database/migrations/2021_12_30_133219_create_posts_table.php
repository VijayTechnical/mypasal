<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug');
            $table->longText('description');
            $table->integer('selling_price')->nullable();
            $table->tinyInteger('is_negotiable')->default(0);
            $table->string('location_id')->nullable();
            $table->tinyInteger('home_delivery')->default(0);
            $table->string('delivery_location')->nullable();
            $table->date('expire_date');
            $table->tinyInteger('is_sold')->default(0);
            $table->enum('status',[0,1])->default(0);
            $table->longText('custom_fields')->nullable();
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('category_id')->unsigned();
            $table->string('condition');
            $table->string('thumbnail')->nullable();
            $table->enum('featured',[0,1])->default(0);
            $table->integer('views')->nullable();
            $table->string('tag')->nullable();
            $table->integer('delivery_charge')->nullable();
            $table->longText('youtube_link');
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
        Schema::dropIfExists('posts');
    }
}

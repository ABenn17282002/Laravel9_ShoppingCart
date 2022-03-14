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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            // shopID(外部制約)、shopid削除後/更新後→自動で更新・削除
            $table->foreignId('shop_id')
            ->constrained()
            ->onUpdate('cascade')
            ->onDelete('cascade');
            // secondary_category_id(外部制約),Primaryは消さないためcascadeなし
            $table->foreignId('secondary_category_id')
            ->constrained();
            // image指定,null許可、カラム名と違うのでテーブル名を指定
            $table->foreignId('image1')
            ->nullable()
            ->constrained('images');
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
        Schema::dropIfExists('products');
    }
};

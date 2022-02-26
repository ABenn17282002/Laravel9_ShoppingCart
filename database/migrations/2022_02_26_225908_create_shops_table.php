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
        Schema::create('shops', function (Blueprint $table) {
            // id
            $table->id();
            // 外部キー制約(owner_idに紐づくもの)
            $table->foreignId('owner_id')->constrained();
            // 名前
            $table->string('name');
            // 情報
            $table->text('information');
            // 画像
            $table->string('filename');
            // 販売 or 停止
            $table->boolean('is_selling');
            // 作成日時
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
        Schema::dropIfExists('shops');
    }
};

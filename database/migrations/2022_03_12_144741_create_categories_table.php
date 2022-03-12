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
        // Primary_tableの定義
        Schema::create('primary_categories', function (Blueprint $table) {
            $table->id();
            // カテゴリー名
            $table->string('name');
            // ソート順
            $table->integer('sort_order');
            $table->timestamps();
        });

        // Secondary_tableの定義
        Schema::create('secondary_categories', function (Blueprint $table) {
            $table->id();
            // カテゴリー名
            $table->string('name');
            // ソート順
            $table->integer('sort_order');
            // 第１カテゴリーID(外部制約キー)
            $table->foreignId('primary_category_id')
            ->constrained();
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
        // Primary_tableとの制約上、先にsecondaryにしないとエラーが出る。
        Schema::dropIfExists('secondary_categories');
        Schema::dropIfExists('primary_categories');
    }
};

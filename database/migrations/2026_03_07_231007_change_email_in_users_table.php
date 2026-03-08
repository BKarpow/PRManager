<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $class) {
            // 1. Видаляємо унікальний індекс.
            // За замовчуванням Laravel називає його 'назваТаблиці_назваПоля_unique'
            $class->dropUnique(['email']);
        });

        Schema::table('users', function (Blueprint $class) {
            // 2. Робимо поле nullable
            // change() дозволяє змінювати параметри існуючої колонки
            $class->string('email')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $class) {
            // Повертаємо все як було: робимо обов'язковим та унікальним
            // Увага: якщо в БД вже є дублікати або null, ця частина видасть помилку
            $class->string('email')->nullable(false)->unique()->change();
        });
    }
};

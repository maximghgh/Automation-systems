<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->foreignId('category_id')
                ->nullable()
                ->constrained('categories')
                ->nullOnDelete();
        });

        DB::table('products')
            ->whereNotNull('subcategory_id')
            ->orderBy('id')
            ->get(['id', 'subcategory_id'])
            ->each(function ($product): void {
                $categoryId = DB::table('subcategories')
                    ->where('id', $product->subcategory_id)
                    ->value('category_id');

                if (! $categoryId) {
                    return;
                }

                DB::table('products')
                    ->where('id', $product->id)
                    ->whereNull('category_id')
                    ->update(['category_id' => $categoryId]);
            });

        DB::table('category_product')
            ->orderBy('id')
            ->get(['product_id', 'category_id'])
            ->each(function ($link): void {
                DB::table('products')
                    ->where('id', $link->product_id)
                    ->whereNull('category_id')
                    ->update(['category_id' => $link->category_id]);
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropConstrainedForeignId('category_id');
        });
    }
};

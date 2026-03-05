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
            $table->foreignId('subcategory_id')
                ->nullable()
                ->after('brand_id')
                ->constrained('subcategories')
                ->nullOnDelete();
        });

        $defaultSubcategoryByCategory = [];

        DB::table('category_product')
            ->orderBy('id')
            ->get(['product_id', 'category_id'])
            ->each(function ($link) use (&$defaultSubcategoryByCategory) {
                $currentSubcategoryId = DB::table('products')
                    ->where('id', $link->product_id)
                    ->value('subcategory_id');

                if ($currentSubcategoryId !== null) {
                    return;
                }

                if (! isset($defaultSubcategoryByCategory[$link->category_id])) {
                    $existingId = DB::table('subcategories')
                        ->where('category_id', $link->category_id)
                        ->where('name', 'Основная')
                        ->value('id');

                    if (! $existingId) {
                        $existingId = DB::table('subcategories')->insertGetId([
                            'category_id' => $link->category_id,
                            'name' => 'Основная',
                            'sort_order' => 0,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }

                    $defaultSubcategoryByCategory[$link->category_id] = $existingId;
                }

                DB::table('products')
                    ->where('id', $link->product_id)
                    ->update(['subcategory_id' => $defaultSubcategoryByCategory[$link->category_id]]);
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropConstrainedForeignId('subcategory_id');
        });
    }
};

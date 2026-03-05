<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('products')
            ->select('id', 'description', 'content')
            ->orderBy('id')
            ->chunkById(100, function ($products) {
                foreach ($products as $product) {
                    $hasTabs = DB::table('product_tabs')
                        ->where('product_id', $product->id)
                        ->exists();

                    if ($hasTabs) {
                        continue;
                    }

                    $description = trim((string) $product->description);
                    $characteristics = trim((string) $product->content);

                    $rows = [];
                    $now = now();

                    if ($description !== '') {
                        $rows[] = [
                            'product_id' => $product->id,
                            'title' => 'Описание',
                            'content' => $product->description,
                            'sort_order' => 0,
                            'created_at' => $now,
                            'updated_at' => $now,
                        ];
                    }

                    if ($characteristics !== '') {
                        $rows[] = [
                            'product_id' => $product->id,
                            'title' => 'Характеристики',
                            'content' => $product->content,
                            'sort_order' => count($rows),
                            'created_at' => $now,
                            'updated_at' => $now,
                        ];
                    }

                    if ($rows !== []) {
                        DB::table('product_tabs')->insert($rows);
                    }
                }
            }, 'id');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Intentionally left empty: tab content may be edited after migration.
    }
};

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
        $defaultTypes = ['Обращение', 'Заявка'];
        $timestamp = now();

        foreach ($defaultTypes as $type) {
            $exists = DB::table('email_types')
                ->where('type', $type)
                ->exists();

            if (! $exists) {
                DB::table('email_types')->insert([
                    'type' => $type,
                    'created_at' => $timestamp,
                    'updated_at' => $timestamp,
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('email_types')
            ->whereIn('type', ['Обращение', 'Заявка'])
            ->delete();
    }
};

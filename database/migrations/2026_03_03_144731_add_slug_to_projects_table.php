<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->string('slug')->nullable();
        });

        $usedSlugs = [];

        DB::table('projects')
            ->select('id', 'title')
            ->orderBy('id')
            ->chunkById(100, function ($projects) use (&$usedSlugs) {
                foreach ($projects as $project) {
                    $baseSlug = Str::slug((string) $project->title);

                    if ($baseSlug === '') {
                        $baseSlug = 'project';
                    }

                    $slug = $baseSlug;
                    $suffix = 1;

                    while (isset($usedSlugs[$slug])) {
                        $slug = $baseSlug.'-'.$suffix;
                        $suffix++;
                    }

                    $usedSlugs[$slug] = true;

                    DB::table('projects')
                        ->where('id', $project->id)
                        ->update(['slug' => $slug]);
                }
            }, 'id');

        Schema::table('projects', function (Blueprint $table) {
            $table->unique('slug');
        });

        DB::statement('ALTER TABLE projects ALTER COLUMN slug SET NOT NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropUnique('projects_slug_unique');
            $table->dropColumn('slug');
        });
    }
};

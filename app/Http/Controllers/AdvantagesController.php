<?php

namespace App\Http\Controllers;

use App\Models\AboutPage;

class AdvantagesController extends Controller
{
    public function index()
    {
        $about = AboutPage::query()->find(1);

        $advantagesItems = collect($about?->advantages_items ?? [])
            ->map(fn ($item) => [
                'title' => $item['title'] ?? null,
                'text' => $item['text'] ?? null,
                'icon' => $item['icon'] ?? null,
            ])
            ->values()
            ->all();

        return view('advantages.index', compact('advantagesItems'));
    }
}

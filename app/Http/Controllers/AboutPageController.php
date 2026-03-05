<?php

namespace App\Http\Controllers;

use App\Models\AboutPage;

class AboutPageController extends Controller
{
    public function show()
    {
        $about = AboutPage::query()->find(1);

        $data = [
            'banner_title' => $about?->banner_title ?? 'О компании',
            'banner_text' => $about?->banner_text,
            'company_text' => $about?->company_text,
            'advantages_items' => collect($about?->advantages_items ?? $this->getDefaultAdvantagesItems())
                ->map(fn ($item) => [
                    'title' => $item['title'] ?? null,
                    'text' => $item['text'] ?? null,
                    'icon' => $item['icon'] ?? null,
                ])
                ->values()
                ->all(),
            'catalog_title' => $about?->catalog_title ?? 'Перейти в каталог',
            'catalog_text' => $about?->catalog_text,
        ];

        return response()->json($data);
    }

    private function getDefaultAdvantagesItems(): array
    {
        return [
            ['title' => 'Широкий ассортимент', 'text' => null, 'icon' => null],
            ['title' => 'Контроль качества', 'text' => null, 'icon' => null],
            ['title' => 'Продуманная логистика', 'text' => null, 'icon' => null],
            ['title' => 'Прием заявок', 'text' => null, 'icon' => null],
        ];
    }
}

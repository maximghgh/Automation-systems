<?php

namespace App\Http\Controllers;

use App\Models\AboutPage;
use App\Support\RichText;

class AboutPageController extends Controller
{
    public function show()
    {
        $about = AboutPage::query()->find(1);

        $data = [
            'banner_title' => RichText::toHtml($about?->banner_title) ?: 'О компании',
            'banner_text' => RichText::toHtml($about?->banner_text),
            'company_text' => RichText::toHtml($about?->company_text),
            'advantages_items' => collect($about?->advantages_items ?? $this->getDefaultAdvantagesItems())
                ->map(fn ($item) => [
                    'title' => RichText::normalize($item['title'] ?? null),
                    'text' => RichText::toHtml($item['text'] ?? null),
                    'icon' => $item['icon'] ?? null,
                ])
                ->values()
                ->all(),
            'catalog_title' => RichText::normalize($about?->catalog_title) ?? 'Перейти в каталог',
            'catalog_text' => RichText::toHtml($about?->catalog_text),
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

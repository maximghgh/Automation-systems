<?php

namespace App\Support;

use Illuminate\Support\Str;

/**
 * Хелпер для текстов из MarkdownEditor:
 *  - normalize(): убирает «склеивающие» пробелы (&nbsp; и т.п.), чтобы текст переносился;
 *  - toHtml(): нормализует и рендерит Markdown в HTML для вывода в вёрстке.
 *
 * Данные в БД не меняются — преобразование только при выводе.
 */
class RichText
{
    /**
     * Заменяет неразрывные/нулевой ширины пробелы на обычные.
     */
    public static function normalize(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        // U+00A0 nbsp, U+202F narrow nbsp, U+2007 figure space, U+2060 word joiner, U+FEFF BOM
        return preg_replace('/[\x{00A0}\x{202F}\x{2007}\x{2060}\x{FEFF}]/u', ' ', $value);
    }

    /**
     * Markdown -> HTML (с предварительной нормализацией). Пустая строка для пустого значения.
     */
    public static function toHtml(?string $value): string
    {
        if ($value === null || trim($value) === '') {
            return '';
        }

        return Str::markdown(self::normalize($value));
    }
}

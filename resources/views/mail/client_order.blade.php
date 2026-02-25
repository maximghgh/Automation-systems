<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Ваш заказ</title>
</head>
<body>
<h1>Спасибо за заявку!</h1>
<p>Ваша заявка обрабаботке</p>
<h2>Ваши товары:</h2>
<ul>
    @foreach ($items as $item)
        <li>
            <strong>Название:</strong> {{ $item['name'] }}
            (<a style="text-decoration: none; color: blue" href="{{ $item['url'] }}">Ссылка на товар</a>)<br>
            @if (isset($item['photo']))
                <img src="{{ $item['photo'] }}" alt="{{ $item['name'] }}" style="max-width: 200px;"><br>
            @endif
            @if (!empty($item['options']) && is_array($item['options']))
                <ul>
                    @foreach ($item['options'] as $option)
                        @php
                            $optionValue = $option['values'][0]['value'] ?? null;
                        @endphp
                        @if (!empty($option['name']) || !empty($optionValue))
                            <li>
                                <strong>{{ $option['name'] ?? 'Опция' }}:</strong> {{ $optionValue ?? '-' }}
                            </li>
                        @endif
                    @endforeach
                </ul>
            @endif
            <strong>Количество:</strong> {{ $item['quantity'] ?? 1 }}
            @if (! $loop->last)
                <div>------------------</div>
            @endif
        </li>
    @endforeach
</ul>
</body>
</html>

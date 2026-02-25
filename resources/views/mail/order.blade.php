<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Новый заказ</title>
</head>
<body>
<h1>Новый заказ</h1>

<h2>Информация о пользователе</h2>
@foreach ($userInfo as $user)
    <p><strong>Имя:</strong> {{ $user['name'] }}</p>
    <p><strong>Телефон:</strong> {{ $user['phone'] }}</p>
    <p><strong>Email:</strong> {{ $user['email'] }}</p>
@endforeach

<h2>Товары</h2>
<ul>
    @foreach ($items as $item)
        <li>
            <strong>Название:</strong> {{ $item['name'] }}
            (<a style="text-decoration: none; color: blue" href="{{ $item['url'] }}">Ссылка на товар</a>)<br>
            @if (isset($item['photo_url']))
                <img src="{{ $item['photo_url'] }}" alt="{{ $item['name'] }}" style="max-width: 200px;"><br>
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

@php
    $hasKeoData =
        (
            isset($keoInfo) &&
            (
                (isset($keoInfo['city']) && $keoInfo['city'] !== '') ||
                (isset($keoInfo['company_name']) && $keoInfo['company_name'] !== '') ||
                (isset($keoInfo['job_title']) && $keoInfo['job_title'] !== '') ||
                (isset($keoInfo['object_address']) && $keoInfo['object_address'] !== '') ||
                (isset($keoInfo['contact_method']) && $keoInfo['contact_method'] !== '') ||
                (
                    isset($keoInfo['request_features']) &&
                    is_array($keoInfo['request_features']) &&
                    count($keoInfo['request_features']) > 0
                ) ||
                (isset($keoInfo['description']) && $keoInfo['description'] !== '')
            )
        )
        ||
        (
            isset($attachment) &&
            is_array($attachment) &&
            count($attachment) > 0
        );
@endphp

@if ($hasKeoData)
    <h3>Проекты пользователя</h3>

    <ul>

        @if (isset($keoInfo['description']) && $keoInfo['description'] !== '')
            <li>
                <strong>Описание проекта: </strong>{{ $keoInfo['description'] }}
            </li>
        @endif
    </ul>

    @if (isset($attachment) && is_array($attachment) && count($attachment) > 0)
        <h3>Прикреплённые файлы</h3>
        <ul>
            @foreach ($attachment as $file)
                <li>{{ $file['original_name'] ?? basename($file['path']) }}</li>
            @endforeach
        </ul>
    @endif
@endif
</body>
</html>

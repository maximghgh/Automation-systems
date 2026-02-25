<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Тест отправки заявки</title>
    <style>
        body {
            margin: 0;
            padding: 24px;
            font-family: Arial, sans-serif;
            background: #f5f6f8;
            color: #1f2937;
        }
        .container {
            max-width: 980px;
            margin: 0 auto;
            background: #fff;
            border-radius: 10px;
            padding: 24px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
        }
        h1, h2 {
            margin-top: 0;
        }
        .grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px;
        }
        .field {
            margin-bottom: 14px;
        }
        label {
            display: block;
            margin-bottom: 6px;
            font-weight: 600;
            font-size: 14px;
        }
        input[type="text"],
        input[type="email"],
        input[type="number"],
        select,
        textarea {
            width: 100%;
            box-sizing: border-box;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            padding: 10px 12px;
            font-size: 14px;
        }
        textarea {
            min-height: 120px;
            resize: vertical;
        }
        .products {
            margin-top: 16px;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            overflow: hidden;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
        }
        th, td {
            padding: 12px;
            border-bottom: 1px solid #e5e7eb;
            text-align: left;
            vertical-align: middle;
        }
        th {
            background: #f9fafb;
            font-size: 13px;
        }
        tr:last-child td {
            border-bottom: none;
        }
        .submit-row {
            margin-top: 20px;
        }
        button {
            border: 0;
            border-radius: 8px;
            padding: 12px 18px;
            background: #1f6feb;
            color: #fff;
            font-weight: 700;
            cursor: pointer;
        }
        .alert {
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 14px;
            font-size: 14px;
        }
        .alert-success {
            background: #ecfdf3;
            color: #166534;
            border: 1px solid #bbf7d0;
        }
        .alert-error {
            background: #fef2f2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }
        .error-list {
            margin: 0;
            padding-left: 18px;
        }
        .hint {
            margin-top: 4px;
            color: #6b7280;
            font-size: 12px;
        }
        .small-input {
            width: 90px;
        }
        @media (max-width: 760px) {
            .grid {
                grid-template-columns: 1fr;
            }
            th, td {
                padding: 10px;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Тестовая форма отправки заказа менеджеру</h1>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-error">
            <ul class="error-list">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('order-request.send') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="grid">
            <div class="field">
                <label for="name">ФИО *</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required>
            </div>

            <div class="field">
                <label for="phone">Телефон *</label>
                <input type="text" id="phone" name="phone" value="{{ old('phone') }}" required>
            </div>
        </div>

        <div class="grid">
            <div class="field">
                <label for="email">Email *</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required>
            </div>

            <div class="field">
                <label for="agreement">Согласие на обработку ПД *</label>
                <div>
                    <input type="checkbox" id="agreement" name="agreement" value="1" @checked(old('agreement'))>
                    <label for="agreement" style="display: inline; font-weight: 400;">Согласен</label>
                </div>
            </div>
        </div>

        <div class="field">
            <label for="description">Описание проекта *</label>
            <textarea id="description" name="description" required>{{ old('description') }}</textarea>
        </div>

        <div class="field">
            <label for="files">Файлы (до 5 шт, до 15MB каждый)</label>
            <input type="file" id="files" name="files[]" multiple>
            <div class="hint">Разрешено: pdf, jpg, jpeg, png, doc, docx, dwg, dxf, xls, xlsx, zip, rar</div>
        </div>

        <h2>Выбор товаров (массив products)</h2>
        <div class="products">
            <table>
                <thead>
                <tr>
                    <th>Выбрать</th>
                    <th>Товар</th>
                    <th>Количество</th>
                </tr>
                </thead>
                <tbody>
                @forelse (($newProducts ?? collect()) as $product)
                    <tr>
                        <td>
                            <input
                                type="checkbox"
                                name="products[{{ $product->id }}][selected]"
                                value="1"
                                @checked(old("products.{$product->id}.selected"))
                            >
                        </td>
                        <td>
                            <strong>{{ $product->title }}</strong><br>
                            <a href="{{ route('products.show', $product) }}" target="_blank">
                                {{ route('products.show', $product) }}
                            </a>
                        </td>
                        <td>
                            <input
                                class="small-input"
                                type="number"
                                name="products[{{ $product->id }}][quantity]"
                                min="1"
                                value="{{ old("products.{$product->id}.quantity", 1) }}"
                            >
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3">Товары не найдены</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="submit-row">
            <button type="submit">Отправить тестовую заявку</button>
        </div>
    </form>
</div>
</body>
</html>

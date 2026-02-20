<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Оставить заявку</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 30px; max-width: 600px; margin: 0 auto; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 6px; font-weight: 600; }
        input, textarea, select, button {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-sizing: border-box;
        }
        textarea { min-height: 120px; resize: vertical; }
        button {
            background: #111827;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover { opacity: 0.9; }
        .alert {
            padding: 10px 12px;
            border-radius: 8px;
            margin-bottom: 15px;
        }
        .alert-success { background: #ecfdf5; color: #065f46; border: 1px solid #a7f3d0; }
        .alert-error { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }
        .errors { margin: 0; padding-left: 18px; }
    </style>
</head>
<body>

<h1>Оставить заявку</h1>

{{-- Успешное сообщение --}}
@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

{{-- Ошибки валидации --}}
@if($errors->any())
    <div class="alert alert-error">
        <strong>Ошибки:</strong>
        <ul class="errors">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('form-request.call') }}" method="POST">
    @csrf

    <div class="form-group">
        <label for="name">Имя</label>
        <input
            type="text"
            name="name"
            id="name"
            value="{{ old('name') }}"
            required
            maxlength="255"
        >
    </div>

    <div class="form-group">
        <label for="phone">Телефон</label>
        <input
            type="text"
            name="phone"
            id="phone"
            value="{{ old('phone') }}"
            required
            maxlength="50"
        >
    </div>

    <div class="form-group">
        <label for="message">Сообщение</label>
        <textarea name="message" id="message">{{ old('message') }}</textarea>
    </div>

    <button type="submit">Отправить заявку</button>
</form>

</body>
</html>

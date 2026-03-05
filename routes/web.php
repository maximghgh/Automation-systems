<?php

use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\HomeController;
use App\Http\Controllers\AboutPageController;
use App\Http\Controllers\AdvantagesController;
use App\Http\Controllers\Api\CallControllers;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\ServicesController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\SearchController;


// путь для главной страницы (вывод для баннера, слайдера, бренды, новинок, проекты, доставка, транспортные компании)
Route::get('/', [HomeController::class, 'index']);

// путь для страницы "О компании" 
Route::get('/about', [AboutPageController::class, 'show'])
    ->name('about.show');

// путь для страницы подробнее о услуги
Route::get('/services/{service}', [ServicesController::class, 'show'])
    ->name('services.show');

// путь для страницы подробнее о товаре
Route::get('/products/{product}', [ProductController::class, 'show'])
    ->name('products.show');

// Проекты

// путь для вывода карточек проектов
Route::get('/projects', [ProjectController::class, 'index'])
    ->name('projects.index');

// путь для страницы подробнее о проекте
Route::get('/projects/{project}', [ProjectController::class, 'show'])
    ->name('projects.show');

// Поиск

// путь для поиска
Route::get('/search', [SearchController::class, 'search'])->name('search');

// Приемущества

// путь для страницы преимуществ
Route::get('/advantages', [AdvantagesController::class, 'index'])
    ->name('advantages.index');

// Формы 

// путь отправки сообщения менеджеру с формы обращения
Route::post('/form-request', [CallControllers::class, 'call'])
    ->name('form-request.call');

// Корзина

// путь отправки сообщения меннеджеру с корзины    
Route::post('/order-request', [OrderController::class, 'submitForm'])
    ->name('order-request.send');

// путь на страницу корзины (только верстка)
Route::view('/basket', 'basket')->name('basket');
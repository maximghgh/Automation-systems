<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Системы автоматизации</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
  <link rel="stylesheet" href="css/style.css" />
  <style>
    .main-head {
      --main-head-bg-image: url('/assets/2c8c4bb8cfc36f85dd070c153118f566794a76e5.jpg');
    }

    .main-head::before {
      background-image: var(--main-head-bg-image);
    }

    .main-form__submit {
      border: 0;
      cursor: pointer;
    }

    .main-form__error {
      margin-left: 6px;
      font-size: 10px;
      color: #BC5555;
      display: none;
    }

    .main-form__error.is-visible {
      display: block;
    }

    .main-form__input.is-invalid {
      border-color: #BC5555;
      color: #BC5555;
    }

    .main-form__input.is-invalid::placeholder {
      color: #BC5555;
    }
  </style>
</head>
<body>
  @include('components.header')

  <main class="page">
    @php
      $mediaUrl = static function ($path, $fallback = '') {
        if (empty($path)) {
          return $fallback;
        }

        if (\Illuminate\Support\Str::startsWith($path, ['http://', 'https://', '/'])) {
          return $path;
        }

        if (\Illuminate\Support\Str::startsWith($path, 'storage/')) {
          return '/' . $path;
        }

        return \Illuminate\Support\Facades\Storage::disk('public')->url($path);
      };

      $banners = \App\Models\Banner::query()
        ->orderBy('id')
        ->get(['title', 'description', 'image']);
      $mainBanner = $banners->first();

    @endphp

    <section class="main-head" style="--main-head-bg-image: url('{{ $mediaUrl($mainBanner?->image, '/assets/2c8c4bb8cfc36f85dd070c153118f566794a76e5.jpg') }}');">
      <div class="main-head__container">
        <div class="main-head__info">
          <h1 class="main-head__title">{{ $mainBanner?->title ?? 'Ваши задачи - наши решения!' }}</h1>
          <p class="main-head__text">{{ $mainBanner?->description ?? 'Специализируемся на поставках оборудования для автоматизированных систем управления технологическими процессами.' }}</p>
        </div>
        <div class="btn btn__white main-head__btn">Перейти в каталог</div>
      </div>
      </div>
    </section>

    <section class="main-about_us">
      <h2 class="main-about_us__title main__title">О компании</h2>
      <div class="main-about_us__container">
        <div class="main-about_us__content">
          <p class="main-about_us__number"><span>01</span>/<span class="main-about_us__number-total">07</span></p>
          <p class="main-about_us__subtitle">Ваши задачи – наши решения!</p>
          <p class="main-about_us__text">На нашем сайте системы автоматизации в разделе продукция вы сможете найти любые приборы контроля для промышленной автоматики, датчики, регуляторы, реле. С помощью раздела сделать запрос вы сможете заказать нужную вам продукцию, имеющуюся у нас на сайте, а так же задать свой вопрос, наши менеджеры непременно в кратчайшие сроки с вами свяжутся.</p>
        </div>
        <div class="main-about_us__slider">
          <button type="button" class="main-about_us__nav main-about_us__prev" aria-label="Предыдущий слайд"></button>
          <div class="main-about_us__slider-swiper swiper">
            <div class="swiper-wrapper">
              @forelse($slides ?? collect() as $slide)
                <div class="swiper-slide main-about_us__slide" data-subtitle="{{ $slide->title }}" data-text="{{ $slide->description }}">
                  <img src="{{ $mediaUrl($slide->image, '/assets/2c8c4bb8cfc36f85dd070c153118f566794a76e5.jpg') }}" alt="{{ $slide->title }}">
                </div>
              @empty
              <div class="swiper-slide main-about_us__slide" data-subtitle="Ваши задачи – наши решения!" data-text="На нашем сайте системы автоматизации в разделе продукция вы сможете найти любые приборы контроля для промышленной автоматики, датчики, регуляторы, реле. С помощью раздела сделать запрос вы сможете заказать нужную вам продукцию, имеющуюся у нас на сайте, а так же задать свой вопрос, наши менеджеры непременно в кратчайшие сроки с вами свяжутся.">
                <img src="/assets/2c8c4bb8cfc36f85dd070c153118f566794a76e5.jpg" alt="Производственная линия">
              </div>
              <div class="swiper-slide main-about_us__slide" data-subtitle="Комплексные поставки" data-text="Комплектуем проекты под ключ: подбираем совместимые позиции, резервируем складские остатки и планируем поставку под сроки вашего запуска.">
                <img src="/assets/2c8c4bb8cfc36f85dd070c153118f566794a76e5.jpg" alt="Оборудование на производстве">
              </div>
              <div class="swiper-slide main-about_us__slide" data-subtitle="Подбор оборудования" data-text="Помогаем выбрать датчики, регуляторы и исполнительные устройства под конкретные условия эксплуатации и требования вашего проекта.">
                <img src="/assets/2c8c4bb8cfc36f85dd070c153118f566794a76e5.jpg" alt="Инженерный контроль оборудования">
              </div>
              <div class="swiper-slide main-about_us__slide" data-subtitle="Техническая консультация" data-text="Разбираем задачу вместе с вашей командой, предлагаем рабочие варианты и готовим рекомендации по внедрению без лишних рисков.">
                <img src="/assets/2c8c4bb8cfc36f85dd070c153118f566794a76e5.jpg" alt="Панель управления">
              </div>
              <div class="swiper-slide main-about_us__slide" data-subtitle="Оперативная логистика" data-text="Организуем отгрузки по графику, отслеживаем статус заказа и быстро реагируем, если нужно скорректировать сроки или состав поставки.">
                <img src="/assets/2c8c4bb8cfc36f85dd070c153118f566794a76e5.jpg" alt="Поставка промышленного оборудования">
              </div>
              <div class="swiper-slide main-about_us__slide" data-subtitle="Надежное партнерство" data-text="Строим долгосрочную работу с клиентами: прозрачные условия, стабильное качество поставок и поддержка на каждом этапе проекта.">
                <img src="/assets/2c8c4bb8cfc36f85dd070c153118f566794a76e5.jpg" alt="Сервис промышленной автоматики">
              </div>
              <div class="swiper-slide main-about_us__slide" data-subtitle="Связь в кратчайшие сроки" data-text="Оставьте запрос на сайте, и наши менеджеры оперативно свяжутся с вами, чтобы согласовать решение, сроки и дальнейшие шаги.">
                <img src="/assets/2c8c4bb8cfc36f85dd070c153118f566794a76e5.jpg" alt="Автоматизированная система управления">
              </div>
              @endforelse
            </div>
          </div>
          <button type="button" class="main-about_us__nav main-about_us__next" aria-label="Следующий слайд"></button>
        </div>
      </div>
    </section>

    <section class="main-products">
      <h2 class="main__title main__title-block">Продукция</h2>
      <div class="main-products__head">
        <p>ООО «Системы автоматизации»<br>Официальный представитель:</p>
        <a href="#" class="btn btn__blue main-products__btn">Показать все</a>
      </div>
      <div class="main-products__catalog">
        @forelse($brands ?? collect() as $brand)
          <div class="main-products__item">
            <div class="main-products__img">
              <img src="{{ $mediaUrl($brand->image, '/assets/02cdaf796a3e94c7c7bfc4a8a987b9f69287b5af.png') }}" alt="{{ $brand->name }}">
            </div>
            <p class="main-products__name">{{ $brand->name }}</p>
          </div>
        @empty
        <a href="#" class="main-products__item">
          <div class="main-products__img">
            <img src="/assets/02cdaf796a3e94c7c7bfc4a8a987b9f69287b5af.png" alt="">
          </div>
          <p class="main-products__name">Delta Electronics</p>
        </a>
        <div class="main-products__item">
          <div class="main-products__img">
            <img src="/assets/02cdaf796a3e94c7c7bfc4a8a987b9f69287b5af.png" alt="">
          </div>
          <p class="main-products__name">Delta Electronics</p>
        </div>
        <div class="main-products__item">
          <div class="main-products__img">
            <img src="/assets/02cdaf796a3e94c7c7bfc4a8a987b9f69287b5af.png" alt="">
          </div>
          <p class="main-products__name">Delta Electronics</p>
        </div>
        <div class="main-products__item">
          <div class="main-products__img">
            <img src="/assets/02cdaf796a3e94c7c7bfc4a8a987b9f69287b5af.png" alt="">
          </div>
          <p class="main-products__name">Delta Electronics</p>
        </div>
        <div class="main-products__item">
          <div class="main-products__img">
            <img src="/assets/02cdaf796a3e94c7c7bfc4a8a987b9f69287b5af.png" alt="">
          </div>
          <p class="main-products__name">Delta Electronics</p>
        </div>
        <div class="main-products__item">
          <div class="main-products__img">
            <img src="/assets/02cdaf796a3e94c7c7bfc4a8a987b9f69287b5af.png" alt="">
          </div>
          <p class="main-products__name">Delta Electronics</p>
        </div>
        <div class="main-products__item">
          <div class="main-products__img">
            <img src="/assets/02cdaf796a3e94c7c7bfc4a8a987b9f69287b5af.png" alt="">
          </div>
          <p class="main-products__name">Delta Electronics</p>
        </div>
        <div class="main-products__item">
          <div class="main-products__img">
            <img src="/assets/02cdaf796a3e94c7c7bfc4a8a987b9f69287b5af.png" alt="">
          </div>
          <p class="main-products__name">Delta Electronics</p>
        </div>
        <div class="main-products__item">
          <div class="main-products__img">
            <img src="/assets/02cdaf796a3e94c7c7bfc4a8a987b9f69287b5af.png" alt="">
          </div>
          <p class="main-products__name">Delta Electronics</p>
        </div>
        <div class="main-products__item">
          <div class="main-products__img">
            <img src="/assets/02cdaf796a3e94c7c7bfc4a8a987b9f69287b5af.png" alt="">
          </div>
          <p class="main-products__name">Delta Electronics</p>
        </div>
        @endforelse
      </div>
    </section>

    <section class="main-new">
      <div class="main-new__head">
        <h2 class="main__title">Новинки</h2>
        <div class="main-new__btns">
          <div class="main-new__slides">
            <button type="button" class="main-about_us__nav main-about_us__prev main-new__prev" aria-label="Предыдущий слайд"></button>
            <button type="button" class="main-about_us__nav main-about_us__next main-new__next" aria-label="Следующий слайд"></button>
          </div>
          <a href="#" class="btn btn__blue main-products__btn" style="width: 220px;">
            Перейти в каталог
            <svg xmlns="http://www.w3.org/2000/svg" width="19" height="19" viewBox="0 0 19 19" fill="none">
            <path d="M14.1867 9.19899C14.149 9.10181 14.0925 9.01303 14.0204 8.93774L10.0621 4.9794C9.98827 4.90559 9.90064 4.84704 9.8042 4.80709C9.70775 4.76714 9.60439 4.74658 9.5 4.74658C9.28918 4.74658 9.08699 4.83033 8.93792 4.9794C8.8641 5.05322 8.80555 5.14085 8.7656 5.23729C8.72565 5.33373 8.70509 5.4371 8.70509 5.54149C8.70509 5.75231 8.78884 5.9545 8.93792 6.10357L11.5504 8.70815H5.54167C5.3317 8.70815 5.13034 8.79156 4.98187 8.94003C4.83341 9.0885 4.75 9.28986 4.75 9.49982C4.75 9.70978 4.83341 9.91115 4.98187 10.0596C5.13034 10.2081 5.3317 10.2915 5.54167 10.2915H11.5504L8.93792 12.8961C8.86371 12.9697 8.80482 13.0572 8.76463 13.1537C8.72444 13.2502 8.70374 13.3536 8.70374 13.4582C8.70374 13.5627 8.72444 13.6661 8.76463 13.7626C8.80482 13.8591 8.86371 13.9466 8.93792 14.0202C9.01151 14.0944 9.09907 14.1533 9.19554 14.1935C9.29202 14.2337 9.39549 14.2544 9.5 14.2544C9.60451 14.2544 9.70798 14.2337 9.80446 14.1935C9.90093 14.1533 9.98849 14.0944 10.0621 14.0202L14.0204 10.0619C14.0925 9.98661 14.149 9.89783 14.1867 9.80065C14.2658 9.60791 14.2658 9.39173 14.1867 9.19899Z" fill="white"/>
            </svg>
          </a>
        </div>
      </div>
      <div class="main-new__slider-swiper swiper">
        <div class="main-new__grid swiper-wrapper">
        @forelse($newProducts ?? collect() as $product)
        <div class="main-new__item swiper-slide">
          <div class="main-new__img">
            <img src="{{ $mediaUrl($product->image, '/assets/272582fd9c288dc352c6e9e18a1c2ebd46283542.png') }}" alt="{{ $product->title }}">
          </div>
          <p class="main-new__name">{{ $product->title }}</p>
          <p class="main-projects__text">{{ $product->short_description }}</p>
          <a href="{{ route('products.show', $product) }}" class="btn btn__blue main-new__btn">Подробнее</a>
        </div>
        @empty
        <div class="main-new__item swiper-slide">
          <div class="main-new__img">
            <img src="/assets/2c8c4bb8cfc36f85dd070c153118f566794a76e5.jpg" alt="">
          </div>
          <p class="main-new__name">ПР205 программируемое реле с графическим дисплеем и Ethernet ОВЕН</p>
          <div class="btn btn__blue main-new__btn">Подробнее</div>
        </div>
        <div class="main-new__item swiper-slide">
          <div class="main-new__img">
            <img src="/assets/272582fd9c288dc352c6e9e18a1c2ebd46283542.png" alt="">
          </div>
          <p class="main-new__name">ПР205 программируемое реле с графическим дисплеем и Ethernet ОВЕН</p>
          <div class="btn btn__blue main-new__btn">Подробнее</div>
        </div>
        <div class="main-new__item swiper-slide">
          <div class="main-new__img">
            <img src="/assets/272582fd9c288dc352c6e9e18a1c2ebd46283542.png" alt="">
          </div>
          <p class="main-new__name">ПР205 программируемое реле с графическим дисплеем и Ethernet ОВЕН</p>
          <div class="btn btn__blue main-new__btn">Подробнее</div>
        </div>
        <div class="main-new__item swiper-slide">
          <div class="main-new__img">
            <img src="/assets/272582fd9c288dc352c6e9e18a1c2ebd46283542.png" alt="">
          </div>
          <p class="main-new__name">ПР205 программируемое реле с графическим дисплеем и Ethernet ОВЕН</p>
          <div class="btn btn__blue main-new__btn">Подробнее</div>
        </div>
        <div class="main-new__item swiper-slide">
          <div class="main-new__img">
            <img src="/assets/272582fd9c288dc352c6e9e18a1c2ebd46283542.png" alt="">
          </div>
          <p class="main-new__name">ПР205 программируемое реле с графическим дисплеем и Ethernet ОВЕН</p>
          <div class="btn btn__blue main-new__btn">Подробнее</div>
        </div>
        @endforelse
      </div>
      </div>
    </section>

    <section class="main-form">
      <div class="main-form__container">
        <div class="main-form__content">
          <h2 class="main__title" style="color: white;">Оставить заявку</h2>
          <p class="main-form__text">Наши менеджеры свяжутся с Вами и проконсультируют по всем интересующим вопросам</p>
        </div>
        <form class="main-form__form" action="{{ route('form-request.call') }}" method="POST" novalidate data-call-form>
          @csrf
          @if(isset($emailTypes) && $emailTypes->isNotEmpty())
            <input type="hidden" name="email_type" value="{{ old('email_type', $emailTypes->first()->id) }}">
          @endif
          <div class="main-form__item">
            <p class="main-form__name">ФИО</p>
            <input type="text" name="name" class="header__search main-form__input @error('name') is-invalid @enderror" placeholder="ФИО" value="{{ old('name') }}" data-form-field="name" aria-invalid="@error('name')true@else false @enderror">
            <p class="main-form__error @error('name') is-visible @enderror" data-form-error="name">@error('name'){{ $message }}@enderror</p>
          </div>
          <div class="main-form__item">
            <p class="main-form__name">Номер телефона</p>
            <input type="tel" name="phone" class="header__search main-form__input @error('phone') is-invalid @enderror" placeholder="+7 (...) ...-..-.." value="{{ old('phone') }}" data-form-field="phone" aria-invalid="@error('phone')true@else false @enderror">
            <p class="main-form__error @error('phone') is-visible @enderror" data-form-error="phone">@error('phone'){{ $message }}@enderror</p>
          </div>
          <div class="main-form__item">
            <p class="main-form__name">Комментарий</p>
            <textarea name="message" class="header__search main-form__input main-form__textarea @error('message') is-invalid @enderror" placeholder="Расскажите нам подробнее о ваших пожеланиях" data-form-field="message" aria-invalid="@error('message')true@else false @enderror">{{ old('message') }}</textarea>
            <p class="main-form__error @error('message') is-visible @enderror" data-form-error="message">@error('message'){{ $message }}@enderror</p>
          </div>
          <div class="main-form__checkbox">
            <div>
              <input type="checkbox" name="consent" value="1" {{ old('consent') ? 'checked' : '' }}>
            </div>
            <p>Я соглашаюсь на <a href="#">обработку персональных данных</a></p>
          </div>
          <button type="submit" class="btn btn__blue main-form__submit">Отправить заявку</button>
        </form>
      </div>
    </section>

    <section class="main-projects">
      <div class="main-new__head">
        <h2 class="main__title">Проекты</h2>
        <div class="main-new__btns">
          <div class="main-new__slides">
            <button type="button" class="main-about_us__nav main-about_us__prev main-new__prev" aria-label="Предыдущий слайд"></button>
            <button type="button" class="main-about_us__nav main-about_us__next main-new__next" aria-label="Следующий слайд"></button>
          </div>
          <a href="{{ route('projects.index') }}" class="btn btn__blue main-products__btn" style="width: 220px;">
            Узнать больше
            <svg xmlns="http://www.w3.org/2000/svg" width="19" height="19" viewBox="0 0 19 19" fill="none">
            <path d="M14.1867 9.19899C14.149 9.10181 14.0925 9.01303 14.0204 8.93774L10.0621 4.9794C9.98827 4.90559 9.90064 4.84704 9.8042 4.80709C9.70775 4.76714 9.60439 4.74658 9.5 4.74658C9.28918 4.74658 9.08699 4.83033 8.93792 4.9794C8.8641 5.05322 8.80555 5.14085 8.7656 5.23729C8.72565 5.33373 8.70509 5.4371 8.70509 5.54149C8.70509 5.75231 8.78884 5.9545 8.93792 6.10357L11.5504 8.70815H5.54167C5.3317 8.70815 5.13034 8.79156 4.98187 8.94003C4.83341 9.0885 4.75 9.28986 4.75 9.49982C4.75 9.70978 4.83341 9.91115 4.98187 10.0596C5.13034 10.2081 5.3317 10.2915 5.54167 10.2915H11.5504L8.93792 12.8961C8.86371 12.9697 8.80482 13.0572 8.76463 13.1537C8.72444 13.2502 8.70374 13.3536 8.70374 13.4582C8.70374 13.5627 8.72444 13.6661 8.76463 13.7626C8.80482 13.8591 8.86371 13.9466 8.93792 14.0202C9.01151 14.0944 9.09907 14.1533 9.19554 14.1935C9.29202 14.2337 9.39549 14.2544 9.5 14.2544C9.60451 14.2544 9.70798 14.2337 9.80446 14.1935C9.90093 14.1533 9.98849 14.0944 10.0621 14.0202L14.0204 10.0619C14.0925 9.98661 14.149 9.89783 14.1867 9.80065C14.2658 9.60791 14.2658 9.39173 14.1867 9.19899Z" fill="white"/>
            </svg>
          </a>
        </div>
      </div>
      <div class="main-projects__grid">
        @forelse($projects ?? collect() as $project)
        <a href="{{ route('projects.show', ['project' => $project->id]) }}" class="main-projects__item">
          <div class="main-projects__img">
            <img src="{{ $mediaUrl($project->image, '/assets/8a3498b50a8a24e9ac4c8f7a7474eacde5407137.png') }}" alt="{{ $project->title }}">
          </div>
          <p class="main-projects__name">{{ $project->title }}</p>
          <p class="main-projects__text">{{ $project->description }}</p>
          <div class="main-projects__link">
            <p>Читать подробнее</p>
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
            <path d="M14.9333 9.68333C14.8937 9.58103 14.8342 9.48758 14.7583 9.40833L10.5917 5.24166C10.514 5.16396 10.4217 5.10233 10.3202 5.06028C10.2187 5.01823 10.1099 4.99658 10 4.99658C9.77808 4.99658 9.56525 5.08474 9.40833 5.24166C9.33063 5.31936 9.269 5.4116 9.22695 5.51312C9.1849 5.61464 9.16326 5.72344 9.16326 5.83333C9.16326 6.05524 9.25141 6.26807 9.40833 6.42499L12.1583 9.16666H5.83333C5.61232 9.16666 5.40036 9.25446 5.24408 9.41074C5.0878 9.56702 5 9.77898 5 9.99999C5 10.221 5.0878 10.433 5.24408 10.5892C5.40036 10.7455 5.61232 10.8333 5.83333 10.8333H12.1583L9.40833 13.575C9.33023 13.6525 9.26823 13.7446 9.22592 13.8462C9.18362 13.9477 9.16183 14.0566 9.16183 14.1667C9.16183 14.2767 9.18362 14.3856 9.22592 14.4871C9.26823 14.5887 9.33023 14.6809 9.40833 14.7583C9.4858 14.8364 9.57797 14.8984 9.67952 14.9407C9.78107 14.983 9.88999 15.0048 10 15.0048C10.11 15.0048 10.2189 14.983 10.3205 14.9407C10.422 14.8984 10.5142 14.8364 10.5917 14.7583L14.7583 10.5917C14.8342 10.5124 14.8937 10.419 14.9333 10.3167C15.0167 10.1138 15.0167 9.88621 14.9333 9.68333Z" fill="#3873A9"/>
            </svg>
        </div>
        </a>
        @empty
        <div class="main-projects__item">
          <div class="main-projects__img">
            <img src="/assets/8a3498b50a8a24e9ac4c8f7a7474eacde5407137.png" alt="">
          </div>
          <p class="main-projects__name">Прибор 1</p>
          <p class="main-projects__text">
            Промышленная автоматика отличается от систем электроники специальной технологией изготовления микросхем автоматика отличается от систем электроники специальной технологией изготовления микросхем
          </p>
          <a href="#" class="main-projects__link">
            <p>Читать подробнее</p>
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
            <path d="M14.9333 9.68333C14.8937 9.58103 14.8342 9.48758 14.7583 9.40833L10.5917 5.24166C10.514 5.16396 10.4217 5.10233 10.3202 5.06028C10.2187 5.01823 10.1099 4.99658 10 4.99658C9.77808 4.99658 9.56525 5.08474 9.40833 5.24166C9.33063 5.31936 9.269 5.4116 9.22695 5.51312C9.1849 5.61464 9.16326 5.72344 9.16326 5.83333C9.16326 6.05524 9.25141 6.26807 9.40833 6.42499L12.1583 9.16666H5.83333C5.61232 9.16666 5.40036 9.25446 5.24408 9.41074C5.0878 9.56702 5 9.77898 5 9.99999C5 10.221 5.0878 10.433 5.24408 10.5892C5.40036 10.7455 5.61232 10.8333 5.83333 10.8333H12.1583L9.40833 13.575C9.33023 13.6525 9.26823 13.7446 9.22592 13.8462C9.18362 13.9477 9.16183 14.0566 9.16183 14.1667C9.16183 14.2767 9.18362 14.3856 9.22592 14.4871C9.26823 14.5887 9.33023 14.6809 9.40833 14.7583C9.4858 14.8364 9.57797 14.8984 9.67952 14.9407C9.78107 14.983 9.88999 15.0048 10 15.0048C10.11 15.0048 10.2189 14.983 10.3205 14.9407C10.422 14.8984 10.5142 14.8364 10.5917 14.7583L14.7583 10.5917C14.8342 10.5124 14.8937 10.419 14.9333 10.3167C15.0167 10.1138 15.0167 9.88621 14.9333 9.68333Z" fill="#3873A9"/>
            </svg>
          </a>
        </div>
        <div class="main-projects__item">
          <div class="main-projects__img">
            <img src="/assets/8a3498b50a8a24e9ac4c8f7a7474eacde5407137.png" alt="">
          </div>
          <p class="main-projects__name">Прибор 1</p>
          <p class="main-projects__text">
            Промышленная автоматика отличается от систем электроники специальной технологией изготовления микросхем автоматика отличается от систем электроники специальной технологией изготовления микросхем
          </p>
          <a href="#" class="main-projects__link">
            <p>Читать подробнее</p>
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
            <path d="M14.9333 9.68333C14.8937 9.58103 14.8342 9.48758 14.7583 9.40833L10.5917 5.24166C10.514 5.16396 10.4217 5.10233 10.3202 5.06028C10.2187 5.01823 10.1099 4.99658 10 4.99658C9.77808 4.99658 9.56525 5.08474 9.40833 5.24166C9.33063 5.31936 9.269 5.4116 9.22695 5.51312C9.1849 5.61464 9.16326 5.72344 9.16326 5.83333C9.16326 6.05524 9.25141 6.26807 9.40833 6.42499L12.1583 9.16666H5.83333C5.61232 9.16666 5.40036 9.25446 5.24408 9.41074C5.0878 9.56702 5 9.77898 5 9.99999C5 10.221 5.0878 10.433 5.24408 10.5892C5.40036 10.7455 5.61232 10.8333 5.83333 10.8333H12.1583L9.40833 13.575C9.33023 13.6525 9.26823 13.7446 9.22592 13.8462C9.18362 13.9477 9.16183 14.0566 9.16183 14.1667C9.16183 14.2767 9.18362 14.3856 9.22592 14.4871C9.26823 14.5887 9.33023 14.6809 9.40833 14.7583C9.4858 14.8364 9.57797 14.8984 9.67952 14.9407C9.78107 14.983 9.88999 15.0048 10 15.0048C10.11 15.0048 10.2189 14.983 10.3205 14.9407C10.422 14.8984 10.5142 14.8364 10.5917 14.7583L14.7583 10.5917C14.8342 10.5124 14.8937 10.419 14.9333 10.3167C15.0167 10.1138 15.0167 9.88621 14.9333 9.68333Z" fill="#3873A9"/>
            </svg>
          </a>
        </div>
        <div class="main-projects__item">
          <div class="main-projects__img">
            <img src="/assets/8a3498b50a8a24e9ac4c8f7a7474eacde5407137.png" alt="">
          </div>
          <p class="main-projects__name">Прибор 1</p>
          <p class="main-projects__text">
            Промышленная автоматика отличается от систем электроники специальной технологией изготовления микросхем автоматика отличается от систем электроники специальной технологией изготовления микросхем
          </p>
          <a href="#" class="main-projects__link">
            <p>Читать подробнее</p>
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
            <path d="M14.9333 9.68333C14.8937 9.58103 14.8342 9.48758 14.7583 9.40833L10.5917 5.24166C10.514 5.16396 10.4217 5.10233 10.3202 5.06028C10.2187 5.01823 10.1099 4.99658 10 4.99658C9.77808 4.99658 9.56525 5.08474 9.40833 5.24166C9.33063 5.31936 9.269 5.4116 9.22695 5.51312C9.1849 5.61464 9.16326 5.72344 9.16326 5.83333C9.16326 6.05524 9.25141 6.26807 9.40833 6.42499L12.1583 9.16666H5.83333C5.61232 9.16666 5.40036 9.25446 5.24408 9.41074C5.0878 9.56702 5 9.77898 5 9.99999C5 10.221 5.0878 10.433 5.24408 10.5892C5.40036 10.7455 5.61232 10.8333 5.83333 10.8333H12.1583L9.40833 13.575C9.33023 13.6525 9.26823 13.7446 9.22592 13.8462C9.18362 13.9477 9.16183 14.0566 9.16183 14.1667C9.16183 14.2767 9.18362 14.3856 9.22592 14.4871C9.26823 14.5887 9.33023 14.6809 9.40833 14.7583C9.4858 14.8364 9.57797 14.8984 9.67952 14.9407C9.78107 14.983 9.88999 15.0048 10 15.0048C10.11 15.0048 10.2189 14.983 10.3205 14.9407C10.422 14.8984 10.5142 14.8364 10.5917 14.7583L14.7583 10.5917C14.8342 10.5124 14.8937 10.419 14.9333 10.3167C15.0167 10.1138 15.0167 9.88621 14.9333 9.68333Z" fill="#3873A9"/>
            </svg>
          </a>
        </div>
        <div class="main-projects__item">
          <div class="main-projects__img">
            <img src="/assets/8a3498b50a8a24e9ac4c8f7a7474eacde5407137.png" alt="">
          </div>
          <p class="main-projects__name">Прибор 1</p>
          <p class="main-projects__text">
            Промышленная автоматика отличается от систем электроники специальной технологией изготовления микросхем автоматика отличается от систем электроники специальной технологией изготовления микросхем
          </p>
          <a href="#" class="main-projects__link">
            <p>Читать подробнее</p>
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
            <path d="M14.9333 9.68333C14.8937 9.58103 14.8342 9.48758 14.7583 9.40833L10.5917 5.24166C10.514 5.16396 10.4217 5.10233 10.3202 5.06028C10.2187 5.01823 10.1099 4.99658 10 4.99658C9.77808 4.99658 9.56525 5.08474 9.40833 5.24166C9.33063 5.31936 9.269 5.4116 9.22695 5.51312C9.1849 5.61464 9.16326 5.72344 9.16326 5.83333C9.16326 6.05524 9.25141 6.26807 9.40833 6.42499L12.1583 9.16666H5.83333C5.61232 9.16666 5.40036 9.25446 5.24408 9.41074C5.0878 9.56702 5 9.77898 5 9.99999C5 10.221 5.0878 10.433 5.24408 10.5892C5.40036 10.7455 5.61232 10.8333 5.83333 10.8333H12.1583L9.40833 13.575C9.33023 13.6525 9.26823 13.7446 9.22592 13.8462C9.18362 13.9477 9.16183 14.0566 9.16183 14.1667C9.16183 14.2767 9.18362 14.3856 9.22592 14.4871C9.26823 14.5887 9.33023 14.6809 9.40833 14.7583C9.4858 14.8364 9.57797 14.8984 9.67952 14.9407C9.78107 14.983 9.88999 15.0048 10 15.0048C10.11 15.0048 10.2189 14.983 10.3205 14.9407C10.422 14.8984 10.5142 14.8364 10.5917 14.7583L14.7583 10.5917C14.8342 10.5124 14.8937 10.419 14.9333 10.3167C15.0167 10.1138 15.0167 9.88621 14.9333 9.68333Z" fill="#3873A9"/>
            </svg>
          </a>
        </div>
        @endforelse
      </div>
    </section>

    <section class="main-delivery">
      <h2 class="main__title main__title-block">Доставка</h2>
      <div class="main-delivery__grid">
        @forelse($deliveryes ?? collect() as $delivery)
        <div class="main-delivery__item {{ $loop->index === 1 ? 'main-delivery__item-blue' : '' }}">
          <div class="main-delivery__icon {{ $loop->index === 1 ? 'main-delivery__icon-white' : '' }}">
            <img src="{{ $mediaUrl($delivery->icon) }}" alt="{{ $delivery->title }}" style="width: 24px; height: 24px; object-fit: contain;">
          </div>
          <p class="main-delivery__name">{{ $delivery->title }}</p>
          <p class="main-delivery__text">
            {{ $delivery->description }}
          </p>
        </div>
        @empty
        <div class="main-delivery__item">
          <div class="main-delivery__icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
            <path d="M22.93 11.65L22.87 11.54C22.8528 11.4905 22.8292 11.4435 22.8 11.4L20.4 8.2C20.1206 7.82741 19.7582 7.525 19.3416 7.31672C18.9251 7.10844 18.4657 7 18 7H16V6C16 5.20435 15.6839 4.44129 15.1213 3.87868C14.5587 3.31607 13.7956 3 13 3H4C3.20435 3 2.44129 3.31607 1.87868 3.87868C1.31607 4.44129 1 5.20435 1 6V17C1 17.2652 1.10536 17.5196 1.29289 17.7071C1.48043 17.8946 1.73478 18 2 18H3C3 18.7956 3.31607 19.5587 3.87868 20.1213C4.44129 20.6839 5.20435 21 6 21C6.79565 21 7.55871 20.6839 8.12132 20.1213C8.68393 19.5587 9 18.7956 9 18H15C15 18.7956 15.3161 19.5587 15.8787 20.1213C16.4413 20.6839 17.2044 21 18 21C18.7956 21 19.5587 20.6839 20.1213 20.1213C20.6839 19.5587 21 18.7956 21 18H22C22.2652 18 22.5196 17.8946 22.7071 17.7071C22.8946 17.5196 23 17.2652 23 17V12C22.9978 11.8801 22.9741 11.7615 22.93 11.65ZM6 19C5.80222 19 5.60888 18.9414 5.44443 18.8315C5.27998 18.7216 5.15181 18.5654 5.07612 18.3827C5.00043 18.2 4.98063 17.9989 5.01921 17.8049C5.0578 17.6109 5.15304 17.4327 5.29289 17.2929C5.43275 17.153 5.61093 17.0578 5.80491 17.0192C5.99889 16.9806 6.19996 17.0004 6.38268 17.0761C6.56541 17.1518 6.72159 17.28 6.83147 17.4444C6.94135 17.6089 7 17.8022 7 18C7 18.2652 6.89464 18.5196 6.70711 18.7071C6.51957 18.8946 6.26522 19 6 19ZM14 16H8.22C7.93882 15.6906 7.59609 15.4435 7.21378 15.2743C6.83148 15.1052 6.41805 15.0178 6 15.0178C5.58195 15.0178 5.16852 15.1052 4.78622 15.2743C4.40391 15.4435 4.06118 15.6906 3.78 16H3V6C3 5.73478 3.10536 5.48043 3.29289 5.29289C3.48043 5.10536 3.73478 5 4 5H13C13.2652 5 13.5196 5.10536 13.7071 5.29289C13.8946 5.48043 14 5.73478 14 6V16ZM16 9H18C18.1552 9 18.3084 9.03615 18.4472 9.10557C18.5861 9.175 18.7069 9.2758 18.8 9.4L20 11H16V9ZM18 19C17.8022 19 17.6089 18.9414 17.4444 18.8315C17.28 18.7216 17.1518 18.5654 17.0761 18.3827C17.0004 18.2 16.9806 17.9989 17.0192 17.8049C17.0578 17.6109 17.153 17.4327 17.2929 17.2929C17.4327 17.153 17.6109 17.0578 17.8049 17.0192C17.9989 16.9806 18.2 17.0004 18.3827 17.0761C18.5654 17.1518 18.7216 17.28 18.8315 17.4444C18.9414 17.6089 19 17.8022 19 18C19 18.2652 18.8946 18.5196 18.7071 18.7071C18.5196 18.8946 18.2652 19 18 19ZM21 16H20.22C19.6879 15.414 18.9457 15.0621 18.1553 15.0209C17.3649 14.9797 16.5902 15.2526 16 15.78V13H21V16Z" fill="white"/>
            </svg>
          </div>
          <p class="main-delivery__name">Способы доставки</p>
          <p class="main-delivery__text">
            Доставка осуществляется транспортными компаниями по всей России. Для крупных заказов возможна доставка собственным транспортом компании.
          </p>
        </div>
        <div class="main-delivery__item main-delivery__item-blue">
          <div class="main-delivery__icon main-delivery__icon-white">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
            <path d="M12 2C10.0222 2 8.08879 2.58649 6.4443 3.6853C4.79981 4.78412 3.51809 6.3459 2.76121 8.17317C2.00433 10.0004 1.8063 12.0111 2.19215 13.9509C2.578 15.8907 3.53041 17.6725 4.92894 19.0711C6.32746 20.4696 8.10929 21.422 10.0491 21.8079C11.9889 22.1937 13.9996 21.9957 15.8268 21.2388C17.6541 20.4819 19.2159 19.2002 20.3147 17.5557C21.4135 15.9112 22 13.9778 22 12C22 10.6868 21.7413 9.38642 21.2388 8.17317C20.7363 6.95991 19.9997 5.85752 19.0711 4.92893C18.1425 4.00035 17.0401 3.26375 15.8268 2.7612C14.6136 2.25866 13.3132 2 12 2ZM12 20C10.4178 20 8.87104 19.5308 7.55544 18.6518C6.23985 17.7727 5.21447 16.5233 4.60897 15.0615C4.00347 13.5997 3.84504 11.9911 4.15372 10.4393C4.4624 8.88743 5.22433 7.46197 6.34315 6.34315C7.46197 5.22433 8.88743 4.4624 10.4393 4.15372C11.9911 3.84504 13.5997 4.00346 15.0615 4.60896C16.5233 5.21447 17.7727 6.23984 18.6518 7.55544C19.5308 8.87103 20 10.4177 20 12C20 14.1217 19.1572 16.1566 17.6569 17.6569C16.1566 19.1571 14.1217 20 12 20ZM15.1 12.63L13 11.42V7C13 6.73478 12.8946 6.48043 12.7071 6.29289C12.5196 6.10536 12.2652 6 12 6C11.7348 6 11.4804 6.10536 11.2929 6.29289C11.1054 6.48043 11 6.73478 11 7V12C11 12 11 12.08 11 12.12C11.0059 12.1889 11.0228 12.2564 11.05 12.32C11.0706 12.3793 11.0974 12.4363 11.13 12.49C11.1574 12.5468 11.1909 12.6005 11.23 12.65L11.39 12.78L11.48 12.87L14.08 14.37C14.2324 14.4564 14.4048 14.5012 14.58 14.5C14.8014 14.5015 15.0171 14.4296 15.1932 14.2953C15.3693 14.1611 15.4959 13.9722 15.5531 13.7583C15.6103 13.5444 15.5948 13.3176 15.5092 13.1134C15.4236 12.9092 15.2726 12.7392 15.08 12.63H15.1Z" fill="#3873A9"/>
            </svg>
          </div>
          <p class="main-delivery__name">Сроки доставки</p>
          <p class="main-delivery__text">
            Сроки доставки зависят от региона и составляют от 1 до 7 рабочих дней. Для Ижевска и ближайших городов возможна доставка в день заказа.
          </p>
        </div>
        <div class="main-delivery__item">
          <div class="main-delivery__icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
            <path d="M20.47 7.37005C20.47 7.37005 20.47 7.37005 20.47 7.29005L20.41 7.14005C20.3891 7.10822 20.3657 7.07812 20.34 7.05005C20.3133 7.00772 20.2832 6.96759 20.25 6.93005L20.16 6.86005L20 6.78005L12.5 2.15005C12.3411 2.05072 12.1574 1.99805 11.97 1.99805C11.7826 1.99805 11.599 2.05072 11.44 2.15005L4.00002 6.78005L3.91002 6.86005L3.82002 6.93005C3.78687 6.96759 3.75678 7.00772 3.73002 7.05005C3.70431 7.07812 3.68091 7.10822 3.66002 7.14005L3.60002 7.29005C3.60002 7.29005 3.60002 7.29005 3.60002 7.37005C3.59019 7.45644 3.59019 7.54366 3.60002 7.63005V16.3701C3.59968 16.54 3.64266 16.7072 3.72489 16.8559C3.80713 17.0047 3.92591 17.13 4.07002 17.22L11.57 21.85C11.6162 21.8786 11.6669 21.8989 11.72 21.91C11.72 21.91 11.77 21.91 11.8 21.91C11.9692 21.9637 12.1508 21.9637 12.32 21.91C12.32 21.91 12.37 21.91 12.4 21.91C12.4531 21.8989 12.5039 21.8786 12.55 21.85L20 17.22C20.1441 17.13 20.2629 17.0047 20.3452 16.8559C20.4274 16.7072 20.4704 16.54 20.47 16.3701V7.63005C20.4799 7.54366 20.4799 7.45644 20.47 7.37005ZM11 19.21L5.50002 15.8101V9.43005L11 12.82V19.21ZM12 11.09L6.40002 7.63005L12 4.18005L17.6 7.63005L12 11.09ZM18.5 15.8101L13 19.21V12.82L18.5 9.43005V15.8101Z" fill="white"/>
            </svg>
          </div>
          <p class="main-delivery__name">Самовывоз</p>
          <p class="main-delivery__text">
            Вы можете самостоятельно забрать заказ из нашего магазина в Ижевске
          </p>
        </div>
        @endforelse
      </div>
      <div class="main-delivery__transport">
        <p class="main-delivery__transport-title">Транспортные компании</p>
        <div class="main-delivery__transport-list">
          @forelse($companies ?? collect() as $company)
          <div class="main-delivery__transport-item">
            <p class="main-delivery__transport-number">{{ $loop->iteration }}</p>
            <p class="main-delivery__transport-name">{{ $company->name }}</p>
          </div>
          @empty
          <div class="main-delivery__transport-item">
            <p class="main-delivery__transport-number">1</p>
            <p class="main-delivery__transport-name">Компания 1</p>
          </div>
          <div class="main-delivery__transport-item">
            <p class="main-delivery__transport-number">2</p>
            <p class="main-delivery__transport-name">Компания 2</p>
          </div>
          <div class="main-delivery__transport-item">
            <p class="main-delivery__transport-number">3</p>
            <p class="main-delivery__transport-name">Компания 3</p>
          </div>
          <div class="main-delivery__transport-item">
            <p class="main-delivery__transport-number">4</p>
            <p class="main-delivery__transport-name">Компания 4</p>
          </div>
          @endforelse
        </div>
      </div>
    </section>

  </main>

  @include('components.footer')

  <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
  <script src="js/main.js"></script>
  <script>
    (function () {
      var searchWrappers = document.querySelectorAll(".header__search-relative");

      searchWrappers.forEach(function (wrapper) {
        var input = wrapper.querySelector(".header__search");
        var loupeIcon = wrapper.querySelector(".header__search-icon--loupe");
        var clearIcon = wrapper.querySelector(".header__search-icon--clear");

        if (!input || !loupeIcon || !clearIcon) {
          return;
        }

        function updateIcon() {
          var hasValue = input.value.trim().length > 0;
          var isFocused = document.activeElement === input;

          if (hasValue && !isFocused) {
            loupeIcon.style.display = "none";
            clearIcon.style.display = "block";
            return;
          }

          loupeIcon.style.display = "block";
          clearIcon.style.display = "none";
        }

        input.addEventListener("input", updateIcon);
        input.addEventListener("focus", updateIcon);
        input.addEventListener("blur", updateIcon);

        clearIcon.addEventListener("mousedown", function (event) {
          event.preventDefault();
        });

        clearIcon.addEventListener("click", function () {
          input.value = "";
          updateIcon();
        });

        clearIcon.addEventListener("keydown", function (event) {
          if (event.key === "Enter" || event.key === " ") {
            event.preventDefault();
            input.value = "";
            updateIcon();
          }
        });

        updateIcon();
      });
    })();

    (function () {
      var header = document.querySelector(".header");
      var headContainer = header ? header.querySelector(".header__head-container") : null;
      var headerContainer = header ? header.querySelector(".header__container") : null;
      var drawer = header ? header.querySelector(".header__drawer") : null;
      var backdrop = header ? header.querySelector(".header__drawer-backdrop") : null;
      var burger = document.querySelector(".header__burger");
      var drawerMedia = window.matchMedia("(max-width: 1199.98px)");

      if (!header || !burger || !drawer || !backdrop) {
        return;
      }

      function syncHeaderOffsets() {
        if (headContainer) {
          header.style.setProperty("--header-head-height", Math.ceil(headContainer.getBoundingClientRect().height) + "px");
        }
        if (headerContainer) {
          header.style.setProperty("--header-container-height", Math.ceil(headerContainer.getBoundingClientRect().height) + "px");
        }
      }

      function setOpenState(isOpen) {
        var shouldOpen = drawerMedia.matches ? isOpen : false;
        header.classList.toggle("is-mobile-open", shouldOpen);
        burger.classList.toggle("is-active", shouldOpen);
        burger.setAttribute("aria-expanded", shouldOpen ? "true" : "false");
        drawer.setAttribute("aria-hidden", shouldOpen ? "false" : "true");
        backdrop.setAttribute("aria-hidden", shouldOpen ? "false" : "true");
        document.body.classList.toggle("no-scroll", shouldOpen);
      }

      burger.addEventListener("click", function () {
        if (!drawerMedia.matches) {
          return;
        }
        setOpenState(!header.classList.contains("is-mobile-open"));
      });

      backdrop.addEventListener("click", function () {
        setOpenState(false);
      });

      document.addEventListener("keydown", function (event) {
        if (event.key === "Escape") {
          setOpenState(false);
        }
      });

      drawer.querySelectorAll("a").forEach(function (link) {
        link.addEventListener("click", function () {
          if (drawerMedia.matches) {
            setOpenState(false);
          }
        });
      });

      function handleViewportChange() {
        syncHeaderOffsets();
        if (!drawerMedia.matches) {
          setOpenState(false);
        }
      }

      window.addEventListener("resize", syncHeaderOffsets);

      if (typeof drawerMedia.addEventListener === "function") {
        drawerMedia.addEventListener("change", handleViewportChange);
      } else {
        drawerMedia.addListener(handleViewportChange);
      }

      syncHeaderOffsets();
      setOpenState(false);
    })();

    (function () {
      var form = document.querySelector("[data-call-form]");
      if (!form) {
        return;
      }

      function normalizePhoneDigits(value) {
        var digits = (value || "").replace(/\D/g, "");

        if (!digits.length) {
          return "";
        }

        if (digits.charAt(0) === "8") {
          digits = "7" + digits.slice(1);
        } else if (digits.charAt(0) === "9") {
          digits = "7" + digits;
        } else if (digits.charAt(0) !== "7") {
          digits = "7" + digits;
        }

        return digits.slice(0, 11);
      }

      function formatRuPhone(value) {
        var digits = normalizePhoneDigits(value);

        if (!digits.length) {
          return "";
        }

        var localDigits = digits.slice(1);
        var formatted = "+7";

        if (localDigits.length > 0) {
          formatted += " (" + localDigits.slice(0, 3);
        }

        if (localDigits.length >= 3) {
          formatted += ")";
        }

        if (localDigits.length > 3) {
          formatted += " " + localDigits.slice(3, 6);
        }

        if (localDigits.length > 6) {
          formatted += "-" + localDigits.slice(6, 8);
        }

        if (localDigits.length > 8) {
          formatted += "-" + localDigits.slice(8, 10);
        }

        return formatted;
      }

      var fieldMessages = {
        name: {
          required: "Ваше имя"
        },
        phone: {
          required: "Ваш номер телефона",
          invalid: "Введите корректный номер телефона"
        },
        message: {
          required: "Ваш комментарий"
        }
      };

      function getFieldNode(fieldName) {
        return form.querySelector('[data-form-field="' + fieldName + '"]');
      }

      function getErrorNode(fieldName) {
        return form.querySelector('[data-form-error="' + fieldName + '"]');
      }

      function showError(fieldName, message) {
        var field = getFieldNode(fieldName);
        var error = getErrorNode(fieldName);

        if (field) {
          field.classList.add("is-invalid");
          field.setAttribute("aria-invalid", "true");
        }

        if (error) {
          error.textContent = message;
          error.classList.add("is-visible");
        }
      }

      function clearError(fieldName) {
        var field = getFieldNode(fieldName);
        var error = getErrorNode(fieldName);

        if (field) {
          field.classList.remove("is-invalid");
          field.setAttribute("aria-invalid", "false");
        }

        if (error) {
          error.textContent = "";
          error.classList.remove("is-visible");
        }
      }

      function validateField(fieldName) {
        var field = getFieldNode(fieldName);
        if (!field) {
          return true;
        }

        var value = field.value.trim();
        var messages = fieldMessages[fieldName] || {};

        if (!value.length) {
          showError(fieldName, messages.required || "Поле обязательно");
          return false;
        }

        if (fieldName === "phone") {
          var digits = normalizePhoneDigits(value);
          if (digits.length < 11) {
            showError(fieldName, messages.invalid || "Некорректное значение");
            return false;
          }
        }

        clearError(fieldName);
        return true;
      }

      ["name", "phone", "message"].forEach(function (fieldName) {
        var field = getFieldNode(fieldName);
        if (!field) {
          return;
        }

        field.addEventListener("input", function () {
          if (fieldName === "phone") {
            field.value = formatRuPhone(field.value);
          }

          if (field.classList.contains("is-invalid")) {
            validateField(fieldName);
          }
        });

        if (fieldName === "phone") {
          field.addEventListener("focus", function () {
            if (!field.value.trim()) {
              field.value = "+7 (";
            }
          });

          field.addEventListener("blur", function () {
            if (field.value === "+7 (" || field.value === "+7") {
              field.value = "";
            }
          });
        }

        field.addEventListener("blur", function () {
          validateField(fieldName);
        });
      });

      var phoneField = getFieldNode("phone");
      if (phoneField) {
        phoneField.value = formatRuPhone(phoneField.value);
      }

      form.addEventListener("submit", function (event) {
        var isNameValid = validateField("name");
        var isPhoneValid = validateField("phone");
        var isMessageValid = validateField("message");

        if (!isNameValid || !isPhoneValid || !isMessageValid) {
          event.preventDefault();
        }
      });
    })();
  </script>
</body>
</html>





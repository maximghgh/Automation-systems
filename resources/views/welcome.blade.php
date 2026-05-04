<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Системы автоматизации</title>
  @include('components.favicon')
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

    .main-form__submit:disabled {
      cursor: not-allowed;
      opacity: 0.7;
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

    .main-form__message {
      margin: 4px 0 0;
      font-size: 13px;
      line-height: 1.4;
      color: #ffffff;
      display: none;
    }

    .main-form__message.is-visible {
      display: block;
    }

    .main-form__message.is-error {
      color: #BC5555;
    }

    .main-form__message.is-success {
      color: green;
    }

    .main-form__input.is-invalid {
      border-color: #BC5555;
      color: #BC5555;
    }

    .main-form__input.is-invalid::placeholder {
      color: #BC5555;
    }

    .main-form__checkbox input.is-invalid {
      outline: 1px solid #BC5555;
      outline-offset: 2px;
    }

    .main-empty-state {
      grid-column: 1 / -1;
      width: 100%;
      padding: 22px 24px;
      border-radius: 16px;
      border: 1px dashed #9fb7d0;
      background: #f6f9fc;
      text-align: center;
    }

    .main-empty-state__title {
      margin: 0;
      font-size: 22px;
      font-weight: 700;
      color: #3873A9;
    }

    .main-empty-state__text {
      margin: 10px 0 0;
      font-size: 16px;
      line-height: 1.5;
      color: #5f6f81;
    }

    .main-delivery__transport-item.main-empty-state {
      display: block;
    }

    .main-new__item.main-new__item--empty {
      width: 100% !important;
      max-width: none;
      padding: 0;
      background: transparent;
      border-radius: 0;
      box-shadow: none;
    }

    .main-new__item.main-new__item--empty .main-empty-state {
      width: 100%;
      margin: 0;
    }
  </style>
</head>
<body>@include('components.header')

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
        <a href="{{ route('catalog.page') }}" class="btn btn__white main-head__btn">Перейти в каталог</a>
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
              <div class="swiper-slide main-about_us__slide" data-subtitle="О компании" data-text="Информация о компании скоро появится.">
                <img src="/assets/2c8c4bb8cfc36f85dd070c153118f566794a76e5.jpg" alt="О компании">
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
        <a href="{{ route('catalog.page') }}" class="btn btn__blue main-products__btn">Показать все</a>
      </div>
      @php
        $homepageBrands = collect($brands ?? [])->values();
        $homepageBrandsCount = $homepageBrands->count();
        $isCompactProductsCatalog = $homepageBrandsCount > 0 && $homepageBrandsCount < 5;
      @endphp
      <div class="main-products__catalog{{ $isCompactProductsCatalog ? ' main-products__catalog--compact' : '' }}">
        @forelse($homepageBrands as $brand)
          <a href="{{ route('catalog.page', ['tab' => 'brands', 'brands' => $brand->id]) }}" class="main-products__item">
            <div class="main-products__img">
              <img src="{{ $mediaUrl($brand->image, '/assets/02cdaf796a3e94c7c7bfc4a8a987b9f69287b5af.png') }}" alt="{{ $brand->name }}">
            </div>
            <p class="main-products__name">{{ $brand->name }}</p>
          </a>
                @empty
          <div class="main-empty-state">
            <p class="main-empty-state__title">Нет брендов</p>
            <p class="main-empty-state__text">Раздел с брендами скоро появится.</p>
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
          <a href="{{ route('catalog.page') }}" class="btn btn__blue main-products__btn" style="width: 220px;">
            Перейти в каталог
            <svg xmlns="http://www.w3.org/2000/svg" width="19" height="19" viewBox="0 0 19 19" fill="none">
            <path d="M14.1867 9.19899C14.149 9.10181 14.0925 9.01303 14.0204 8.93774L10.0621 4.9794C9.98827 4.90559 9.90064 4.84704 9.8042 4.80709C9.70775 4.76714 9.60439 4.74658 9.5 4.74658C9.28918 4.74658 9.08699 4.83033 8.93792 4.9794C8.8641 5.05322 8.80555 5.14085 8.7656 5.23729C8.72565 5.33373 8.70509 5.4371 8.70509 5.54149C8.70509 5.75231 8.78884 5.9545 8.93792 6.10357L11.5504 8.70815H5.54167C5.3317 8.70815 5.13034 8.79156 4.98187 8.94003C4.83341 9.0885 4.75 9.28986 4.75 9.49982C4.75 9.70978 4.83341 9.91115 4.98187 10.0596C5.13034 10.2081 5.3317 10.2915 5.54167 10.2915H11.5504L8.93792 12.8961C8.86371 12.9697 8.80482 13.0572 8.76463 13.1537C8.72444 13.2502 8.70374 13.3536 8.70374 13.4582C8.70374 13.5627 8.72444 13.6661 8.76463 13.7626C8.80482 13.8591 8.86371 13.9466 8.93792 14.0202C9.01151 14.0944 9.09907 14.1533 9.19554 14.1935C9.29202 14.2337 9.39549 14.2544 9.5 14.2544C9.60451 14.2544 9.70798 14.2337 9.80446 14.1935C9.90093 14.1533 9.98849 14.0944 10.0621 14.0202L14.0204 10.0619C14.0925 9.98661 14.149 9.89783 14.1867 9.80065C14.2658 9.60791 14.2658 9.39173 14.1867 9.19899Z" fill="white"/>
            </svg>
          </a>
        </div>
      </div>
      <div class="main-new__slider-swiper swiper">
        <div class="main-new__grid swiper-wrapper">
        @php
          $homepageNewProducts = collect($newProducts ?? [])->filter(function ($product) {
            if (isset($product->is_new)) {
              return (bool) $product->is_new;
            }

            if (is_array($product) && array_key_exists('is_new', $product)) {
              return (bool) $product['is_new'];
            }

            return true;
          })->values();
        @endphp
        @forelse($homepageNewProducts as $product)
          @php
            $productTitle = data_get($product, 'title') ?? data_get($product, 'name') ?? 'Товар';
            $productImage = data_get($product, 'image') ?? data_get($product, 'photo');
            $productDescription = data_get($product, 'short_description') ?? data_get($product, 'description');
            $productSlug = data_get($product, 'slug');
            $productUrl = data_get($product, 'url');

            if (!$productUrl && !empty($productSlug)) {
              $productUrl = route('products.show', ['product' => $productSlug]);
            }
          @endphp
          <div class="main-new__item swiper-slide">
            <div class="main-new__img">
              <img src="{{ $mediaUrl($productImage, '/assets/272582fd9c288dc352c6e9e18a1c2ebd46283542.png') }}" alt="{{ $productTitle }}">
            </div>
            <p class="main-new__name">{{ $productTitle }}</p>
            @if(!empty($productDescription))
              <p class="main-projects__text">{{ $productDescription }}</p>
            @endif
            <a href="{{ $productUrl ?: '#' }}" class="btn btn__blue main-new__btn">Подробнее</a>
          </div>
        @empty
          <div class="main-new__item swiper-slide main-new__item--empty">
            <div class="main-empty-state">
              <p class="main-empty-state__title">Нет товаров в разделе «Новинки»</p>
              <p class="main-empty-state__text">Новинки скоро появятся.</p>
            </div>
          </div>
        @endforelse
      </div>
      </div>
    </section>

    <section class="main-form" id="main-form">
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
          <div class="main-form__item">
            <div class="main-form__checkbox">
              <div>
                <input type="checkbox" name="consent" value="1" {{ old('consent') ? 'checked' : '' }} data-form-consent aria-invalid="@error('consent')true@else false @enderror">
              </div>
              <p>
                Я соглашаюсь с условиями
                <a href="{{ route('legal.public-offer') }}" target="_blank" rel="noopener noreferrer">публичной оферты</a>,
                <a href="{{ route('legal.privacy') }}" target="_blank" rel="noopener noreferrer">политики конфиденциальности</a>
                и использованием файлов cookie.
              </p>
            </div>
            <p class="main-form__error @error('consent') is-visible @enderror" data-form-error="consent">@error('consent'){{ $message }}@enderror</p>
          </div>
          <button type="submit" class="btn btn__blue main-form__submit">Отправить заявку</button>
          <p class="main-form__message @if(session('success')) is-visible is-success @endif" data-call-form-message @if(!session('success')) hidden @endif>{{ session('success') }}</p>
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
        <div class="main-empty-state">
          <p class="main-empty-state__title">Нет проектов</p>
          <p class="main-empty-state__text">Проекты скоро появятся.</p>
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
        <div class="main-empty-state">
          <p class="main-empty-state__title">Нет данных о доставке</p>
          <p class="main-empty-state__text">Информация о доставке скоро появится.</p>
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
          <div class="main-delivery__transport-item main-empty-state">
            <p class="main-empty-state__title">Нет транспортных компаний</p>
            <p class="main-empty-state__text">Список транспортных компаний скоро появится.</p>
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

      var consentField = form.querySelector("[data-form-consent]");
      var consentError = form.querySelector('[data-form-error="consent"]');
      var formMessage = form.querySelector("[data-call-form-message]");
      var submitButton = form.querySelector(".main-form__submit");
      var defaultSubmitText = submitButton ? submitButton.textContent.trim() : "";
      var isSubmitting = false;

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

      function setFormMessage(message, isError) {
        if (!formMessage) {
          return;
        }

        var hasMessage = typeof message === "string" && message.trim().length > 0;
        formMessage.hidden = !hasMessage;
        formMessage.textContent = hasMessage ? message.trim() : "";
        formMessage.classList.toggle("is-visible", hasMessage);
        formMessage.classList.toggle("is-error", hasMessage && !!isError);
        formMessage.classList.toggle("is-success", hasMessage && !isError);
      }

      function showConsentError(message) {
        if (consentField) {
          consentField.classList.add("is-invalid");
          consentField.setAttribute("aria-invalid", "true");
        }

        if (consentError) {
          consentError.textContent = message;
          consentError.classList.add("is-visible");
        }
      }

      function clearConsentError() {
        if (consentField) {
          consentField.classList.remove("is-invalid");
          consentField.setAttribute("aria-invalid", "false");
        }

        if (consentError) {
          consentError.textContent = "";
          consentError.classList.remove("is-visible");
        }
      }

      function validateConsent() {
        if (!consentField) {
          return true;
        }

        if (!consentField.checked) {
          showConsentError("Примите условия оферты и политики конфиденциальности");
          return false;
        }

        clearConsentError();
        return true;
      }

      function extractFirstError(errors) {
        if (!errors || typeof errors !== "object") {
          return "";
        }

        var keys = Object.keys(errors);
        for (var i = 0; i < keys.length; i += 1) {
          var messages = errors[keys[i]];
          if (Array.isArray(messages) && messages.length > 0) {
            return String(messages[0]);
          }
        }

        return "";
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

          if (formMessage && formMessage.classList.contains("is-error")) {
            setFormMessage("", false);
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

      if (consentField) {
        consentField.addEventListener("change", function () {
          if (consentField.checked) {
            clearConsentError();
          }

          if (formMessage && formMessage.classList.contains("is-error")) {
            setFormMessage("", false);
          }
        });
      }

      form.addEventListener("submit", function (event) {
        event.preventDefault();

        if (isSubmitting) {
          return;
        }

        var isNameValid = validateField("name");
        var isPhoneValid = validateField("phone");
        var isMessageValid = validateField("message");
        var isConsentValid = validateConsent();

        if (!isNameValid || !isPhoneValid || !isMessageValid || !isConsentValid) {
          setFormMessage("Проверьте форму и заполните обязательные поля.", true);
          return;
        }

        var action = form.getAttribute("action") || window.location.href;
        var method = (form.getAttribute("method") || "POST").toUpperCase();
        var formData = new FormData(form);

        setFormMessage("Отправляем заявку...", false);
        isSubmitting = true;

        if (submitButton) {
          submitButton.disabled = true;
          submitButton.textContent = "Отправляем...";
        }

        fetch(action, {
          method: method,
          body: formData,
          headers: {
            Accept: "application/json",
            "X-Requested-With": "XMLHttpRequest"
          }
        })
          .then(function (response) {
            return response
              .json()
              .catch(function () {
                return null;
              })
              .then(function (payload) {
                return {
                  ok: response.ok,
                  payload: payload
                };
              });
          })
          .then(function (result) {
            var payload = result.payload;

            if (!result.ok) {
              if (payload && payload.errors) {
                if (payload.errors.name && payload.errors.name[0]) {
                  showError("name", String(payload.errors.name[0]));
                }
                if (payload.errors.phone && payload.errors.phone[0]) {
                  showError("phone", String(payload.errors.phone[0]));
                }
                if (payload.errors.message && payload.errors.message[0]) {
                  showError("message", String(payload.errors.message[0]));
                }
                if (payload.errors.consent && payload.errors.consent[0]) {
                  showConsentError(String(payload.errors.consent[0]));
                }
              }

              var firstError = extractFirstError(payload && payload.errors);
              var errorMessage = firstError || (payload && payload.message) || "Не удалось отправить заявку.";
              setFormMessage(errorMessage, true);
              return;
            }

            form.reset();
            ["name", "phone", "message"].forEach(function (fieldName) {
              var field = getFieldNode(fieldName);
              if (field) {
                field.value = "";
              }
            });

            if (consentField) {
              consentField.checked = false;
            }

            ["name", "phone", "message"].forEach(clearError);
            clearConsentError();

            if (phoneField) {
              phoneField.value = "";
            }

            setFormMessage((payload && payload.message) || "Заявка отправлена. Мы скоро с вами свяжемся.", false);
          })
          .catch(function () {
            setFormMessage("Ошибка отправки. Проверьте интернет и попробуйте еще раз.", true);
          })
          .finally(function () {
            isSubmitting = false;

            if (submitButton) {
              submitButton.disabled = false;
              submitButton.textContent = defaultSubmitText || "Отправить заявку";
            }
          });
      });
    })();
  </script>
</body>
</html>

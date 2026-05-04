<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Sistemi</title>
  @include('components.favicon')
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
  <link rel="stylesheet" href="{{ asset('css/style.css') }}" />
</head>
<body>@include('components.header')

<main class="page">
    <section class="basket">
      <div class="card__links basket__steps">
        <p class="card__link basket__link active" data-basket-step-link="cart">Корзина</p>
        <div class="card__logo basket__icon active" data-basket-step-icon="cart" aria-hidden="true">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
            <path d="M12.3583 9.40874L8.82501 5.8754C8.74754 5.7973 8.65538 5.7353 8.55383 5.693C8.45228 5.65069 8.34336 5.62891 8.23335 5.62891C8.12334 5.62891 8.01442 5.65069 7.91287 5.693C7.81132 5.7353 7.71915 5.7973 7.64168 5.8754C7.48647 6.03154 7.39935 6.24275 7.39935 6.4629C7.39935 6.68306 7.48647 6.89427 7.64168 7.0504L10.5917 10.0004L7.64168 12.9504C7.48647 13.1065 7.39935 13.3178 7.39935 13.5379C7.39935 13.7581 7.48647 13.9693 7.64168 14.1254C7.71955 14.2026 7.81189 14.2637 7.91342 14.3052C8.01496 14.3467 8.12367 14.3677 8.23335 14.3671C8.34302 14.3677 8.45174 14.3467 8.55327 14.3052C8.6548 14.2637 8.74715 14.2026 8.82501 14.1254L12.3583 10.5921C12.4365 10.5146 12.4984 10.4224 12.5408 10.3209C12.5831 10.2193 12.6048 10.1104 12.6048 10.0004C12.6048 9.89039 12.5831 9.78147 12.5408 9.67992C12.4984 9.57837 12.4365 9.48621 12.3583 9.40874Z" fill="black"/>
          </svg>
        </div>
        <p class="card__link basket__link" data-basket-step-link="details">Детали заказа</p>
        <div class="card__logo basket__icon" data-basket-step-icon="details" aria-hidden="true">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
            <path d="M12.3583 9.40874L8.82501 5.8754C8.74754 5.7973 8.65538 5.7353 8.55383 5.693C8.45228 5.65069 8.34336 5.62891 8.23335 5.62891C8.12334 5.62891 8.01442 5.65069 7.91287 5.693C7.81132 5.7353 7.71915 5.7973 7.64168 5.8754C7.48647 6.03154 7.39935 6.24275 7.39935 6.4629C7.39935 6.68306 7.48647 6.89427 7.64168 7.0504L10.5917 10.0004L7.64168 12.9504C7.48647 13.1065 7.39935 13.3178 7.39935 13.5379C7.39935 13.7581 7.48647 13.9693 7.64168 14.1254C7.71955 14.2026 7.81189 14.2637 7.91342 14.3052C8.01496 14.3467 8.12367 14.3677 8.23335 14.3671C8.34302 14.3677 8.45174 14.3467 8.55327 14.3052C8.6548 14.2637 8.74715 14.2026 8.82501 14.1254L12.3583 10.5921C12.4365 10.5146 12.4984 10.4224 12.5408 10.3209C12.5831 10.2193 12.6048 10.1104 12.6048 10.0004C12.6048 9.89039 12.5831 9.78147 12.5408 9.67992C12.4984 9.57837 12.4365 9.48621 12.3583 9.40874Z" fill="black"/>
          </svg>
        </div>
        <p class="card__link basket__link" data-basket-step-link="request">Отправка заявки</p>
      </div>
      <div class="basket__container">
        <div class="basket__left">
          <div class="basket__products basket__panel basket__panel--cart">
            <div class="main-empty-state">
              <p class="main-empty-state__title">Корзина пуста</p>
              <p class="main-empty-state__text">Добавьте товары из каталога.</p>
            </div>
          </div>
          <div class="basket__details basket__panel basket__panel--details">
            <h2 class="basket__details-title">Оформление заявки</h2>
            <form
              class="basket__form"
              action="{{ route('order-request.send') }}"
              method="post"
              enctype="multipart/form-data"
              novalidate
              data-basket-order-form
            >
              @csrf
              <div class="basket__field basket__field--full">
                <div class="main-form__item">
                    <p class="main-form__name">Ваше имя <span style="color: #d83f3f;">*</span></p>
                    <input type="text" name="name" class="header__search main-form__input" placeholder="Введите ФИО" required data-form-field="name" aria-invalid="false">
                    <p class="main-form__error" data-form-error="name"></p>
                </div>
              </div>
              <div class="basket__field">
                <div class="main-form__item">
                    <p class="main-form__name">Ваше Email <span style="color: #d83f3f;">*</span></p>
                    <input type="email" name="email" class="header__search main-form__input" placeholder="Введите Email" required data-form-field="email" aria-invalid="false">
                    <p class="main-form__error" data-form-error="email"></p>
                </div>
              </div>
              <div class="basket__field">
                <div class="main-form__item">
                    <p class="main-form__name">Ваш телефон <span style="color: #d83f3f;">*</span></p>
                    <input type="tel" name="phone" class="header__search main-form__input" placeholder="+7 (...) ...-..-.." required data-form-field="phone" aria-invalid="false">
                    <p class="main-form__error" data-form-error="phone"></p>
                </div>
              </div>
              <div class="main-form__checkbox basket__field--full">
                    <div>
                    <input type="checkbox" name="consent" value="1" data-basket-agreement required>
                    </div>
                    <p>
                      Я соглашаюсь с условиями
                      <a href="{{ route('legal.public-offer') }}" target="_blank" rel="noopener noreferrer">публичной оферты</a>,
                      <a href="{{ route('legal.privacy') }}" target="_blank" rel="noopener noreferrer">политики конфиденциальности</a>
                      и использованием файлов cookie.
                    </p>
                </div>
              <div class="basket__project basket__field--full">
                <button type="button" class="basket__project-toggle" data-basket-project-toggle aria-expanded="false">
                  <span class="basket__project-title">
                    Мой проект
                    <span class="basket__tooltip" tabindex="0">
                      <span class="basket__tooltip-icon" aria-hidden="true">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                          <path d="M8.00004 7.33398C7.82323 7.33398 7.65366 7.40422 7.52864 7.52925C7.40362 7.65427 7.33338 7.82384 7.33338 8.00065V10.6673C7.33338 10.8441 7.40362 11.0137 7.52864 11.1387C7.65366 11.2637 7.82323 11.334 8.00004 11.334C8.17686 11.334 8.34642 11.2637 8.47145 11.1387C8.59647 11.0137 8.66671 10.8441 8.66671 10.6673V8.00065C8.66671 7.82384 8.59647 7.65427 8.47145 7.52925C8.34642 7.40422 8.17686 7.33398 8.00004 7.33398ZM8.25338 4.72065C8.09107 4.65397 7.90902 4.65397 7.74671 4.72065C7.66488 4.75238 7.59011 4.79996 7.52671 4.86065C7.46782 4.92545 7.42047 4.99986 7.38671 5.08065C7.34939 5.15977 7.33112 5.24653 7.33338 5.33398C7.33287 5.42172 7.34969 5.5087 7.38286 5.58992C7.41604 5.67115 7.46492 5.74502 7.52671 5.80732C7.59151 5.8662 7.66592 5.91356 7.74671 5.94732C7.84771 5.98881 7.95735 6.00486 8.06601 5.99406C8.17466 5.98326 8.279 5.94593 8.36985 5.88536C8.4607 5.8248 8.53529 5.74284 8.58705 5.6467C8.63881 5.55056 8.66617 5.44317 8.66671 5.33398C8.66426 5.15747 8.5952 4.98841 8.47338 4.86065C8.40998 4.79996 8.33521 4.75238 8.25338 4.72065ZM8.00004 1.33398C6.6815 1.33398 5.39257 1.72498 4.29624 2.45752C3.19991 3.19006 2.34543 4.23125 1.84085 5.44943C1.33626 6.6676 1.20424 8.00805 1.46148 9.30125C1.71871 10.5945 2.35365 11.7823 3.286 12.7147C4.21835 13.647 5.40624 14.282 6.69944 14.5392C7.99265 14.7965 9.33309 14.6644 10.5513 14.1598C11.7694 13.6553 12.8106 12.8008 13.5432 11.7045C14.2757 10.6081 14.6667 9.31919 14.6667 8.00065C14.6667 7.12517 14.4943 6.25827 14.1592 5.44943C13.8242 4.64059 13.3331 3.90566 12.7141 3.28661C12.095 2.66755 11.3601 2.17649 10.5513 1.84145C9.74243 1.50642 8.87552 1.33398 8.00004 1.33398ZM8.00004 13.334C6.94521 13.334 5.91406 13.0212 5.037 12.4352C4.15994 11.8491 3.47635 11.0162 3.07269 10.0416C2.66902 9.06709 2.5634 7.99473 2.76919 6.96017C2.97498 5.9256 3.48293 4.97529 4.22881 4.22941C4.97469 3.48353 5.925 2.97558 6.95956 2.7698C7.99413 2.56401 9.06648 2.66963 10.041 3.07329C11.0156 3.47696 11.8485 4.16055 12.4345 5.03761C13.0206 5.91467 13.3334 6.94582 13.3334 8.00065C13.3334 9.41514 12.7715 10.7717 11.7713 11.7719C10.7711 12.7721 9.41453 13.334 8.00004 13.334Z" fill="#818181"/>
                        </svg>
                      </span>
                      <span class="basket__tooltip-content">Опишите проект и прикрепите файлы, чтобы менеджер быстрее подготовил расчет.</span>
                    </span>
                  </span>
                  <svg class="basket__project-arrow" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                    <path d="M5.83337 7.5L10 11.6667L14.1667 7.5" stroke="#1C1C1C" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>
                  </svg>
                </button>
                <div class="basket__project-panel" data-basket-project-panel hidden>
                  <div class="main-form__item">
                        <p class="main-form__name">Описание проекта</p>
                        <textarea name="description" class="header__search main-form__input main-form__textarea" placeholder="Введите описание проекта"></textarea>
                    </div>
                  <div class="main-form__item">
                    <p class="main-form__name">Прикрепить файлы</p>
                    <label class="basket__dropzone" for="basket-files">
                      <input class="basket__dropzone-input" id="basket-files" name="attachment[]" type="file" accept=".pdf,.doc,.docx,.png,.jpg,.jpeg,.zip" multiple data-basket-file-input>
                      <span data-basket-files-placeholder>Загрузите или перетащите файлы (PDF, DOC, DOCX, PNG, JPG, JPEG, ZIP, до 50 МБ на файл)</span>
                    </label>
                    <p class="basket__files-summary" data-basket-files-summary hidden></p>
                    <ul class="basket__files-list" data-basket-files-list hidden></ul>
                  </div>
                </div>
              </div>
              <p class="basket__form-message" data-basket-form-message hidden></p>
            </form>
          </div>
        </div>
        <div class="basket__sum">
          <p class="basket__sum-text">Итоговая сумма будет подтверждена менеджером</p>
          <button type="button" class="btn btn__blue basket__sum-btn" data-basket-next-btn data-basket-text-cart="Запросить стоимость" data-basket-text-details="Отправить запрос" data-basket-empty-title="Внесите товары в корзину">Запросить стоимость</button>
        </div>
      </div>
      <div class="basket__request" data-basket-request-panel>
        <div class="basket__request-placeholder">
            <p class="basket__request-title">Ваша заявка принята!</p>
            <div class="basket__request-check">
                <svg xmlns="http://www.w3.org/2000/svg" width="111" height="81" viewBox="0 0 111 81" fill="none">
                <path d="M107.567 2.31709C106.839 1.58288 105.972 1.00012 105.018 0.602436C104.063 0.204748 103.039 0 102.005 0C100.971 0 99.9471 0.204748 98.9925 0.602436C98.038 1.00012 97.1716 1.58288 96.4434 2.31709L38.0851 60.7538L13.5667 36.1571C12.8106 35.4267 11.9181 34.8524 10.9401 34.467C9.96204 34.0816 8.91767 33.8925 7.86658 33.9107C6.8155 33.9289 5.77829 34.1539 4.81418 34.573C3.85007 34.992 2.97793 35.5968 2.24756 36.3529C1.51719 37.109 0.942897 38.0015 0.557466 38.9796C0.172035 39.9576 -0.016987 41.002 0.00119786 42.0531C0.0193827 43.1041 0.244417 44.1414 0.663449 45.1055C1.08248 46.0696 1.68731 46.9417 2.44339 47.6721L32.5234 77.7521C33.2516 78.4863 34.118 79.069 35.0725 79.4667C36.0271 79.8644 37.051 80.0692 38.0851 80.0692C39.1192 80.0692 40.143 79.8644 41.0976 79.4667C42.0521 79.069 42.9185 78.4863 43.6467 77.7521L107.567 13.8321C108.362 13.0986 108.996 12.2083 109.43 11.2174C109.864 10.2265 110.089 9.15639 110.089 8.07459C110.089 6.99279 109.864 5.92272 109.43 4.9318C108.996 3.94089 108.362 3.05062 107.567 2.31709V2.31709Z" fill="white"/>
                </svg>
            </div>
            <p class="basket__request-text">Скоро с вами свяжется наш менеджер для уточнения деталей заявки</p>
            <a href="/" class="btn btn__blue basket__request-btn">Вернуться на главную страницу</a>
        </div>
      </div>
    </section>
</main>

  @include('components.footer')
  <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
  <script src="{{ asset('js/main.js') }}"></script>
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
  </script>
</body>
</html>

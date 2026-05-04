<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>404 | Системы автоматизации</title>
  @include('components.favicon')
  <link rel="stylesheet" href="{{ asset('css/style.css') }}" />
</head>
<body>@include('components.header')

  <main class="page">
    <section class="error-page">
      <div class="error-page__shell">
        <div class="error-page__content">
          <div class="error-page__intro">
            <span class="error-page__eyebrow">Ошибка 404</span>
            <h1 class="main__title error-page__title">Страница не найдена</h1>
            <p class="error-page__text">
              Похоже, ссылка ведет в несуществующий раздел или страница уже была перемещена.
              Мы поможем быстро вернуться к нужной информации.
            </p>
          </div>

          <div class="error-page__bottom">
            <div class="error-page__actions">
              <a class="btn btn__blue error-page__action" href="{{ url('/') }}">На главную</a>
              <a class="btn btn__white error-page__action" href="{{ route('catalog.page') }}">Открыть каталог</a>
            </div>

            <div class="error-page__links">
              <a class="error-page__link-card" href="{{ route('services.index') }}">
                <span class="error-page__link-title">Услуги</span>
                <span class="error-page__link-text">Подбор, интеграция и техническая поддержка решений.</span>
              </a>
              <a class="error-page__link-card" href="{{ route('projects.index') }}">
                <span class="error-page__link-title">Проекты</span>
                <span class="error-page__link-text">Посмотрите реализованные кейсы и готовые внедрения.</span>
              </a>
              <a class="error-page__link-card" href="{{ route('contact.page') }}">
                <span class="error-page__link-title">Контакты</span>
                <span class="error-page__link-text">Если нужна помощь, свяжитесь с нами удобным способом.</span>
              </a>
            </div>
          </div>
        </div>

        <div class="error-page__visual" aria-hidden="true">
          <div class="error-page__panel">
            <div class="error-page__panel-head">
              <span class="error-page__panel-chip">Навигация</span>
              <span class="error-page__panel-chip error-page__panel-chip--ghost">Вне сети</span>
            </div>

            <div class="error-page__digits">
              <span class="error-page__digit">4</span>
              <span class="error-page__digit error-page__digit--ring">
                <span class="error-page__digit-core"></span>
              </span>
              <span class="error-page__digit">4</span>
            </div>

            <div class="error-page__scheme">
              <div class="error-page__scheme-line error-page__scheme-line--horizontal"></div>
              <div class="error-page__scheme-line error-page__scheme-line--vertical"></div>
              <span class="error-page__scheme-node error-page__scheme-node--top"></span>
              <span class="error-page__scheme-node error-page__scheme-node--right"></span>
              <span class="error-page__scheme-node error-page__scheme-node--bottom"></span>
              <div class="error-page__scheme-card">
                <span class="error-page__scheme-label">Маршрут не найден</span>
                <span class="error-page__scheme-value">Проверьте адрес или перейдите в соседний раздел</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>

  @include('components.footer')

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

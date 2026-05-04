<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>О компании</title>
  @include('components.favicon')
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
  <link rel="stylesheet" href="{{ asset('css/style.css') }}" />
</head>
<body>@include('components.header')

  <main class="page">
    <section class="about_us-head">
      <div class="about_us-head__container">
        <h1 class="about_us-head__title" data-about-banner-title>О компании «Системы автоматизации»</h1>
        <h2 class="about_us-head__text" data-about-banner-text>
          Мы занимаемся разработкой, сборкой и установкой шкафов автоматики из комплектующих ведущих отечественных и зарубежных производителей, оказываем техническую и информационную поддержку.
        </h2>
      </div>
    </section>

    <section class="about_us-description">
      <div class="about_us-description__container">
        <h3 class="about_us-description__title">Системы автоматизации</h3>
        <p class="about_us-description__text" data-about-company-text>
          Специалисты компании «Системы автоматизации» готовы посетить Ваше предприятие, ознакомиться с существующими задачами автоматизации, помочь в подготовке технического задания и в кратчайшие сроки предоставить Вам подробное технико-коммерческое предложение, оказать профессиональную помощь в настройке оборудования.
        </p>
      </div>
    </section>

    <section class="about_us-catalog">
      <div class="about_us-catalog__container">
        <div class="about_us-catalog__left">
          <p class="about_us-catalog__left-text">Наши преимущества</p>
        </div>

        <div class="about_us-catalog__grid">
          <div class="main-delivery__item about_us-catalog__item" data-about-adv-item>
            <div class="main-delivery__icon about_us-catalog__icon" data-about-adv-icon>
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                <path d="M12 2L20 6V18L12 22L4 18V6L12 2ZM12 4.2L6 7.2V16.8L12 19.8L18 16.8V7.2L12 4.2Z" fill="white"/>
              </svg>
            </div>
            <div class="about_us-catalog__info">
              <p class="main-delivery__name" data-about-adv-title>Широкий ассортимент</p>
              <p class="main-delivery__text" data-about-adv-text>Постоянно пополняемый склад продукции</p>
            </div>
          </div>

          <div class="main-delivery__item main-delivery__item-blue about_us-catalog__item" data-about-adv-item>
            <div class="main-delivery__icon main-delivery__icon-white about_us-catalog__icon" data-about-adv-icon>
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                <path d="M12 2L20 6V18L12 22L4 18V6L12 2ZM12 4.2L6 7.2V16.8L12 19.8L18 16.8V7.2L12 4.2Z" fill="#3873A9"/>
              </svg>
            </div>
            <div class="about_us-catalog__info">
              <p class="main-delivery__name" data-about-adv-title>Контроль качества</p>
              <p class="main-delivery__text" data-about-adv-text>Партии проходят многоуровневую проверку перед отгрузкой</p>
            </div>
          </div>

          <div class="main-delivery__item main-delivery__item-blue about_us-catalog__item" data-about-adv-item>
            <div class="main-delivery__icon main-delivery__icon-white about_us-catalog__icon" data-about-adv-icon>
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                <path d="M12 2L20 6V18L12 22L4 18V6L12 2ZM12 4.2L6 7.2V16.8L12 19.8L18 16.8V7.2L12 4.2Z" fill="#3873A9"/>
              </svg>
            </div>
            <div class="about_us-catalog__info">
              <p class="main-delivery__name" data-about-adv-title>Продуманная логистика</p>
              <p class="main-delivery__text" data-about-adv-text>Доставка оплаченных товаров возможна удобным для вас способом</p>
            </div>
          </div>

          <div class="main-delivery__item about_us-catalog__item" data-about-adv-item>
            <div class="main-delivery__icon about_us-catalog__icon" data-about-adv-icon>
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                <path d="M12 2L20 6V18L12 22L4 18V6L12 2ZM12 4.2L6 7.2V16.8L12 19.8L18 16.8V7.2L12 4.2Z" fill="white"/>
              </svg>
            </div>
            <div class="about_us-catalog__info">
              <p class="main-delivery__name" data-about-adv-title>Прием заявок</p>
              <p class="main-delivery__text" data-about-adv-text>Заявки на доставку принимаются с сайта и по телефону</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="about_us-invitation">
      <div class="about_us-invitation__container">
        <p class="about_us-catalog__left-text" style="margin-bottom: 10px;" data-about-catalog-title>Станьте нашим клиентом</p>
        <p class="about_us-invitation__text" data-about-catalog-text>
          На нашем сайте системы автоматизации в разделе продукция вы сможете найти любые приборы контроля для промышленной автоматики, датчики, регуляторы, реле.
        </p>
        <a href="{{ route('catalog.page') }}" class="btn btn__white main-products__btn">Перейти в каталог</a>
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

    (function () {
      var endpoint = @json(route('about.show'));

      function normalizeMediaPath(path) {
        if (!path || typeof path !== "string") {
          return "";
        }

        if (/^https?:\/\//i.test(path) || path.charAt(0) === "/") {
          return path;
        }

        if (path.indexOf("storage/") === 0) {
          return "/" + path;
        }

        return "/storage/" + path;
      }

      function setText(node, value) {
        if (!node || typeof value !== "string") {
          return;
        }

        var trimmed = value.trim();
        if (!trimmed.length) {
          return;
        }

        node.textContent = trimmed;
      }

      fetch(endpoint, {
        headers: {
          "Accept": "application/json"
        }
      })
        .then(function (response) {
          if (!response.ok) {
            throw new Error("Failed to load about page data");
          }

          return response.json();
        })
        .then(function (data) {
          setText(document.querySelector("[data-about-banner-title]"), data.banner_title);
          setText(document.querySelector("[data-about-banner-text]"), data.banner_text);
          setText(document.querySelector("[data-about-company-text]"), data.company_text);
          setText(document.querySelector("[data-about-catalog-title]"), data.catalog_title);
          setText(document.querySelector("[data-about-catalog-text]"), data.catalog_text);

          var cards = document.querySelectorAll("[data-about-adv-item]");
          var items = Array.isArray(data.advantages_items) ? data.advantages_items : [];

          cards.forEach(function (card, index) {
            var item = items[index];
            var titleNode = card.querySelector("[data-about-adv-title]");
            var textNode = card.querySelector("[data-about-adv-text]");
            var iconNode = card.querySelector("[data-about-adv-icon]");

            if (!item) {
              card.style.display = "none";
              return;
            }

            card.style.display = "";

            setText(titleNode, item.title);
            setText(textNode, item.text);

            var textValue = typeof item.text === "string" ? item.text.trim() : "";
            if (textNode) {
              textNode.style.display = textValue.length ? "" : "none";
            }

            var iconSrc = normalizeMediaPath(item.icon);
            if (iconNode && iconSrc) {
              var image = document.createElement("img");
              image.src = iconSrc;
              image.alt = typeof item.title === "string" && item.title.trim().length ? item.title : "Иконка";
              image.style.width = "24px";
              image.style.height = "24px";
              image.style.objectFit = "contain";

              iconNode.innerHTML = "";
              iconNode.appendChild(image);
            }
          });
        })
        .catch(function () {
          // Keep fallback markup if API is unavailable.
        });
    })();
  </script>
</body>
</html>

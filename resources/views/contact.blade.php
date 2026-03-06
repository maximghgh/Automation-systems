<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Контакты</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
  <link rel="stylesheet" href="{{ asset('css/style.css') }}" />
</head>
<body>@include('components.header')

  <main class="page">
    <section class="contact">
      <h2 class="main__title">Как нас найти</h2>
      <div class="contact__container">
        <div class="contact__info">
          <div id="contact-map" class="contact__map" aria-label="Карта с адресом офиса"></div>
          <div class="contact__content">
            <p class="contact__address">426077 Россия, УР, г. Ижевск, ул. Удмуртская, 161А</p>
            <p class="contact__house">Здание торгового центра "Коста-Бланка", вход в центральный офис.</p>
            <p class="contact__work"><span>Время работы:</span> ПН-ЧТ с 09:00 до 17:00, ПТ с 09:00 до 16:00, СБ-ВС выходной</p>
          </div>
          <div class="contact__cosh">
            <div class="contact__cosh-item">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                <path d="M19.44 12.9994C19.22 12.9994 18.99 12.9294 18.77 12.8794C18.3245 12.7812 17.8867 12.6509 17.46 12.4894C16.9961 12.3206 16.4861 12.3294 16.0283 12.514C15.5705 12.6986 15.1971 13.046 14.98 13.4894L14.76 13.9394C13.786 13.3976 12.891 12.7246 12.1 11.9394C11.3148 11.1484 10.6418 10.2534 10.1 9.27938L10.52 8.99938C10.9634 8.7823 11.3108 8.40891 11.4954 7.95107C11.68 7.49323 11.6888 6.98329 11.52 6.51938C11.3612 6.0918 11.2309 5.65418 11.13 5.20938C11.08 4.98938 11.04 4.75938 11.01 4.52938C10.8886 3.825 10.5196 3.18712 9.96962 2.73062C9.41961 2.27412 8.72469 2.02899 8.00999 2.03938H5.00999C4.57903 2.03533 4.15224 2.12419 3.7587 2.29991C3.36516 2.47563 3.0141 2.73408 2.72942 3.05766C2.44474 3.38125 2.23313 3.76237 2.10898 4.17509C1.98484 4.58781 1.95107 5.02244 2.00999 5.44938C2.54273 9.63876 4.45602 13.5313 7.44765 16.512C10.4393 19.4928 14.3387 21.3919 18.53 21.9094H18.91C19.6474 21.9105 20.3594 21.6399 20.91 21.1494C21.2264 20.8664 21.4791 20.5196 21.6515 20.1317C21.8238 19.7438 21.912 19.3238 21.91 18.8994V15.8994C21.8977 15.2048 21.6448 14.5359 21.1943 14.007C20.7439 13.4782 20.1238 13.122 19.44 12.9994ZM19.94 18.9994C19.9398 19.1414 19.9094 19.2817 19.8508 19.411C19.7922 19.5403 19.7067 19.6557 19.6 19.7494C19.4886 19.8464 19.358 19.9188 19.2167 19.9619C19.0754 20.005 18.9266 20.0177 18.78 19.9994C15.0349 19.5192 11.5562 17.8059 8.89271 15.1297C6.22919 12.4535 4.5324 8.96672 4.06999 5.21938C4.05408 5.0729 4.06803 4.92471 4.111 4.78377C4.15397 4.64283 4.22506 4.51207 4.31999 4.39938C4.4137 4.29271 4.52906 4.20722 4.65837 4.1486C4.78769 4.08997 4.92801 4.05956 5.06999 4.05938H8.06999C8.30254 4.05421 8.52962 4.13026 8.71214 4.27445C8.89466 4.41864 9.0212 4.62195 9.06999 4.84938C9.10999 5.12271 9.15999 5.39271 9.21999 5.65938C9.33551 6.18652 9.48925 6.70456 9.67999 7.20938L8.27999 7.85938C8.16029 7.9143 8.05262 7.99233 7.96315 8.08898C7.87369 8.18562 7.80419 8.29899 7.75867 8.42257C7.71314 8.54615 7.69247 8.67751 7.69784 8.8091C7.70322 8.94069 7.73453 9.06992 7.78999 9.18938C9.22919 12.2721 11.7072 14.7502 14.79 16.1894C15.0335 16.2894 15.3065 16.2894 15.55 16.1894C15.6747 16.1448 15.7893 16.0758 15.8872 15.9866C15.985 15.8973 16.0642 15.7895 16.12 15.6694L16.74 14.2694C17.257 14.4543 17.7846 14.6079 18.32 14.7294C18.5867 14.7894 18.8567 14.8394 19.13 14.8794C19.3574 14.9282 19.5607 15.0547 19.7049 15.2372C19.8491 15.4198 19.9252 15.6468 19.92 15.8794L19.94 18.9994Z" fill="#3873A9"/>
              </svg>
              <a href="tel:+73412529298">8 (3412) 52-92-98, 52-92-75, 52-93-39</a>
            </div>
            <div class="contact__cosh-item">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                <path d="M19 4H5C4.20435 4 3.44129 4.31607 2.87868 4.87868C2.31607 5.44129 2 6.20435 2 7V17C2 17.7956 2.31607 18.5587 2.87868 19.1213C3.44129 19.6839 4.20435 20 5 20H19C19.7956 20 20.5587 19.6839 21.1213 19.1213C21.6839 18.5587 22 17.7956 22 17V7C22 6.20435 21.6839 5.44129 21.1213 4.87868C20.5587 4.31607 19.7956 4 19 4ZM18.59 6L12.71 11.88C12.617 11.9737 12.5064 12.0481 12.3846 12.0989C12.2627 12.1497 12.132 12.1758 12 12.1758C11.868 12.1758 11.7373 12.1497 11.6154 12.0989C11.4936 12.0481 11.383 11.9737 11.29 11.88L5.41 6H18.59ZM20 17C20 17.2652 19.8946 17.5196 19.7071 17.7071C19.5196 17.8946 19.2652 18 19 18H5C4.73478 18 4.48043 17.8946 4.29289 17.7071C4.10536 17.5196 4 17.2652 4 17V7.41L9.88 13.29C10.4425 13.8518 11.205 14.1674 12 14.1674C12.795 14.1674 13.5575 13.8518 14.12 13.29L20 7.41V17Z" fill="#3873A9"/>
              </svg>
              <a href="mailto:info@kipdepo.ru">info@kipdepo.ru</a>
            </div>
          </div>
        </div>
        <div class="contact__question">
          <p class="contact__question-name">Остались вопросы?</p>
          <p class="contact__question-text">Свяжитесь с нами любым удобным способом, и мы ответим на все ваши вопросы о доставке, гарантиях и условиях сотрудничества.</p>
          <a href="/#main-form" class="btn btn__white contact__question-btn">Связаться с нами</a>
        </div>
      </div>
    </section>

    <section class="company">
      <div class="company__container">
        <h2 class="main__title">Реквизиты компании</h2>
        <div class="company__info">
          <p class="company__name">Полное наименование организации</p>
          <p class="company__value">Общество с Ограниченной Ответственностью «СИСТЕМЫ АВТОМАТИЗАЦИИ»</p>
          <p class="company__name">Сокращенное наименование организации</p>
          <p class="company__value">ООО «СИСТЕМЫ АВТОМАТИЗАЦИИ»</p>
          <p class="company__name">Юридический адрес</p>
          <p class="company__value">426077 Россия, УР, г. Ижевск, ул. Удмуртская, 161А</p>
          <p class="company__name">ИНН</p>
          <p class="company__value">1841028197</p>
          <p class="company__name">КПП</p>
          <p class="company__value">184101001</p>
          <p class="company__name">Банк</p>
          <p class="company__value">Отделение №8618 Сбербанка России г. Ижевск</p>
          <p class="company__name">БИК</p>
          <p class="company__value">049401601</p>
          <p class="company__name">Расчетный счет</p>
          <p class="company__value">407 028 102 680 000 010 22</p>
          <p class="company__name">Корреспондентский счёт</p>
          <p class="company__value">30101810400000000601</p>
        </div>
      </div>
    </section>

    <div class="company__end"></div>
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
      var mapElement = document.getElementById("contact-map");
      if (!mapElement) {
        return;
      }

      var mapCenter = [56.83755, 53.228378];
      var markerIcon = @json(asset('assets/Point.png'));
      var yandexScriptUrl = "https://api-maps.yandex.ru/2.1/?apikey=38e8671e-3c02-48e7-8432-c204818d7056&lang=ru_RU";

      function initContactMap() {
        var map = new ymaps.Map("contact-map", {
          center: mapCenter,
          zoom: 16,
          controls: []
        }, {
          suppressMapOpenBlock: true
        });

        map.behaviors.disable("scrollZoom");

        var marker = new ymaps.Placemark(mapCenter, {}, {
          iconLayout: "default#image",
          iconImageHref: markerIcon,
          iconImageSize: [50, 72],
          iconImageOffset: [-50, -100]
        });

        map.geoObjects.add(marker);
      }

      function onMapScriptReady() {
        if (typeof ymaps === "undefined") {
          return;
        }
        ymaps.ready(initContactMap);
      }

      var existingMapScript = document.querySelector("script[data-yandex-maps='true']");

      if (existingMapScript) {
        if (typeof ymaps !== "undefined") {
          onMapScriptReady();
        } else {
          existingMapScript.addEventListener("load", onMapScriptReady);
        }
        return;
      }

      var script = document.createElement("script");
      script.src = yandexScriptUrl;
      script.async = true;
      script.setAttribute("data-yandex-maps", "true");
      script.addEventListener("load", onMapScriptReady);
      document.head.appendChild(script);
    })();
  </script>
</body>
</html>

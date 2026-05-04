<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>&#1055;&#1086;&#1080;&#1089;&#1082; &#1087;&#1086; &#1089;&#1072;&#1081;&#1090;&#1091;</title>
  @include('components.favicon')
  <link rel="stylesheet" href="{{ asset('css/style.css') }}" />
</head>
<body>@include('components.header')

  <main class="page">
    <div class="search-page">
      <div class="search-page__head">
        <h1 class="main__title">&#1056;&#1077;&#1079;&#1091;&#1083;&#1100;&#1090;&#1072;&#1090;&#1099; &#1087;&#1086;&#1080;&#1089;&#1082;&#1072;</h1>
        <p class="search-page__meta">
          &#1055;&#1086; &#1079;&#1072;&#1087;&#1088;&#1086;&#1089;&#1091;:
          <span class="search-page__query">{{ $query }}</span>
        </p>
      </div>

      @if($results->isEmpty())
        <div class="main-empty-state">
          <p class="main-empty-state__title">&#1053;&#1080;&#1095;&#1077;&#1075;&#1086; &#1085;&#1077; &#1085;&#1072;&#1081;&#1076;&#1077;&#1085;&#1086;</p>
          <p class="main-empty-state__text">&#1055;&#1086;&#1087;&#1088;&#1086;&#1073;&#1091;&#1081;&#1090;&#1077; &#1080;&#1079;&#1084;&#1077;&#1085;&#1080;&#1090;&#1100; &#1079;&#1072;&#1087;&#1088;&#1086;&#1089;.</p>
        </div>
      @else
        <div class="search-page__list">
          @foreach($results as $item)
            @php
              $resultUrl = (string) ($item['url'] ?? '#');
              $resultTitle = trim((string) ($item['title'] ?? ''));
              $resultType = '&#1056;&#1077;&#1079;&#1091;&#1083;&#1100;&#1090;&#1072;&#1090;';

              if (\Illuminate\Support\Str::contains($resultUrl, '/services/')) {
                  $resultType = '&#1059;&#1089;&#1083;&#1091;&#1075;&#1072;';
              } elseif (\Illuminate\Support\Str::contains($resultUrl, '/projects/')) {
                  $resultType = '&#1055;&#1088;&#1086;&#1077;&#1082;&#1090;';
              } elseif (\Illuminate\Support\Str::contains($resultUrl, ['/catalog/', '/products/'])) {
                  $resultType = '&#1058;&#1086;&#1074;&#1072;&#1088;';
              }
            @endphp

            <a
              class="search-page__result-link"
              href="{{ $resultUrl }}"
              data-search-result-link
              data-search-result-title="{{ $resultTitle }}"
              data-search-result-type="{!! $resultType !!}"
            >
              <span class="search-page__result-title">{{ $resultTitle }}</span>
              <span class="search-page__result-type">{!! $resultType !!}</span>
            </a>
          @endforeach
        </div>
      @endif
    </div>
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

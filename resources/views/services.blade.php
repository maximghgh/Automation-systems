<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>&#1059;&#1089;&#1083;&#1091;&#1075;&#1080;</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
  <link rel="stylesheet" href="{{ asset('css/style.css') }}" />
</head>
<body>@include('components.header')

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
  @endphp

  <main class="page">
    <div class="projects">
      <h2 class="main__title">&#1059;&#1089;&#1083;&#1091;&#1075;&#1080;</h2>
      <div class="projects__container">
        <div class="projects__grid">
          @forelse($services as $service)
            <a href="{{ route('services.show', $service) }}" class="main-projects__item">
              <div class="main-projects__img">
                <img src="{{ $mediaUrl($service->image, '/assets/8a3498b50a8a24e9ac4c8f7a7474eacde5407137.png') }}" alt="{{ $service->title }}">
              </div>
              <p class="main-projects__name">{{ $service->title }}</p>
              <p class="main-projects__text">{{ $service->description }}</p>
              <div class="main-projects__link">
                <p>Читать подробнее</p>
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                  <path d="M14.9333 9.68333C14.8937 9.58103 14.8342 9.48758 14.7583 9.40833L10.5917 5.24166C10.514 5.16396 10.4217 5.10233 10.3202 5.06028C10.2187 5.01823 10.1099 4.99658 10 4.99658C9.77808 4.99658 9.56525 5.08474 9.40833 5.24166C9.33063 5.31936 9.269 5.4116 9.22695 5.51312C9.1849 5.61464 9.16326 5.72344 9.16326 5.83333C9.16326 6.05524 9.25141 6.26807 9.40833 6.42499L12.1583 9.16666H5.83333C5.61232 9.16666 5.40036 9.25446 5.24408 9.41074C5.0878 9.56702 5 9.77898 5 9.99999C5 10.221 5.0878 10.433 5.24408 10.5892C5.40036 10.7455 5.61232 10.8333 5.83333 10.8333H12.1583L9.40833 13.575C9.33023 13.6525 9.26823 13.7446 9.22592 13.8462C9.18362 13.9477 9.16183 14.0566 9.16183 14.1667C9.16183 14.2767 9.18362 14.3856 9.22592 14.4871C9.26823 14.5887 9.33023 14.6809 9.40833 14.7583C9.4858 14.8364 9.57797 14.8984 9.67952 14.9407C9.78107 14.983 9.88999 15.0048 10 15.0048C10.11 15.0048 10.2189 14.983 10.3205 14.9407C10.422 14.8984 10.5142 14.8364 10.5917 14.7583L14.7583 10.5917C14.8342 10.5124 14.8937 10.419 14.9333 10.3167C15.0167 10.1138 15.0167 9.88621 14.9333 9.68333Z" fill="#3873A9"/>
                </svg>
              </div>
            </a>
          @empty
            <div class="main-empty-state">
              <p class="main-empty-state__title">Нет услуг</p>
              <p class="main-empty-state__text">Услуги скоро появятся.</p>
            </div>
          @endforelse
        </div>

        @if($services->hasPages())
          <div class="catalog__pagination">
            @if($services->onFirstPage())
              <div class="catalog__pagination-item" aria-disabled="true">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                  <path d="M11.2899 11.9997L14.8299 8.4597C15.0162 8.27234 15.1207 8.01889 15.1207 7.7547C15.1207 7.49052 15.0162 7.23707 14.8299 7.0497C14.737 6.95598 14.6264 6.88158 14.5045 6.83081C14.3827 6.78004 14.252 6.75391 14.1199 6.75391C13.9879 6.75391 13.8572 6.78004 13.7354 6.83081C13.6135 6.88158 13.5029 6.95598 13.4099 7.0497L9.16994 11.2897C9.07622 11.3827 9.00182 11.4933 8.95105 11.6151C8.90028 11.737 8.87415 11.8677 8.87415 11.9997C8.87415 12.1317 8.90028 12.2624 8.95105 12.3843C9.00182 12.5061 9.07622 12.6167 9.16994 12.7097L13.4099 16.9997C13.5034 17.0924 13.6142 17.1657 13.736 17.2155C13.8579 17.2652 13.9883 17.2905 14.1199 17.2897C14.2516 17.2905 14.382 17.2652 14.5038 17.2155C14.6257 17.1657 14.7365 17.0924 14.8299 16.9997C15.0162 16.8123 15.1207 16.5589 15.1207 16.2947C15.1207 16.0305 15.0162 15.7771 14.8299 15.5897L11.2899 11.9997Z" fill="#1C1C1C"/>
                </svg>
              </div>
            @else
              <a href="{{ $services->previousPageUrl() }}" class="catalog__pagination-item">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                  <path d="M11.2899 11.9997L14.8299 8.4597C15.0162 8.27234 15.1207 8.01889 15.1207 7.7547C15.1207 7.49052 15.0162 7.23707 14.8299 7.0497C14.737 6.95598 14.6264 6.88158 14.5045 6.83081C14.3827 6.78004 14.252 6.75391 14.1199 6.75391C13.9879 6.75391 13.8572 6.78004 13.7354 6.83081C13.6135 6.88158 13.5029 6.95598 13.4099 7.0497L9.16994 11.2897C9.07622 11.3827 9.00182 11.4933 8.95105 11.6151C8.90028 11.737 8.87415 11.8677 8.87415 11.9997C8.87415 12.1317 8.90028 12.2624 8.95105 12.3843C9.00182 12.5061 9.07622 12.6167 9.16994 12.7097L13.4099 16.9997C13.5034 17.0924 13.6142 17.1657 13.736 17.2155C13.8579 17.2652 13.9883 17.2905 14.1199 17.2897C14.2516 17.2905 14.382 17.2652 14.5038 17.2155C14.6257 17.1657 14.7365 17.0924 14.8299 16.9997C15.0162 16.8123 15.1207 16.5589 15.1207 16.2947C15.1207 16.0305 15.0162 15.7771 14.8299 15.5897L11.2899 11.9997Z" fill="#1C1C1C"/>
                </svg>
              </a>
            @endif

            @foreach($services->getUrlRange(1, $services->lastPage()) as $page => $url)
              @if($page == $services->currentPage())
                <div class="catalog__pagination-item active">{{ $page }}</div>
              @else
                <a href="{{ $url }}" class="catalog__pagination-item">{{ $page }}</a>
              @endif
            @endforeach

            @if($services->hasMorePages())
              <a href="{{ $services->nextPageUrl() }}" class="catalog__pagination-item">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                  <path d="M14.8299 11.2897L10.5899 7.0497C10.497 6.95598 10.3864 6.88158 10.2645 6.83081C10.1427 6.78004 10.012 6.75391 9.87994 6.75391C9.74793 6.75391 9.61723 6.78004 9.49537 6.83081C9.37351 6.88158 9.26291 6.95598 9.16994 7.0497C8.98369 7.23707 8.87915 7.49052 8.87915 7.7547C8.87915 8.01889 8.98369 8.27234 9.16994 8.4597L12.7099 11.9997L9.16994 15.5397C8.98369 15.7271 8.87915 15.9805 8.87915 16.2447C8.87915 16.5089 8.98369 16.7623 9.16994 16.9497C9.26338 17.0424 9.3742 17.1157 9.49604 17.1655C9.61787 17.2152 9.74834 17.2405 9.87994 17.2397C10.0115 17.2405 10.142 17.2152 10.2638 17.1655C10.3857 17.1157 10.4965 17.0424 10.5899 16.9497L14.8299 12.7097C14.9237 12.6167 14.9981 12.5061 15.0488 12.3843C15.0996 12.2624 15.1257 12.1317 15.1257 11.9997C15.1257 11.8677 15.0996 11.737 15.0488 11.6151C14.9981 11.4933 14.9237 11.3827 14.8299 11.2897Z" fill="#3873A9"/>
                </svg>
              </a>
            @else
              <div class="catalog__pagination-item" aria-disabled="true">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                  <path d="M14.8299 11.2897L10.5899 7.0497C10.497 6.95598 10.3864 6.88158 10.2645 6.83081C10.1427 6.78004 10.012 6.75391 9.87994 6.75391C9.74793 6.75391 9.61723 6.78004 9.49537 6.83081C9.37351 6.88158 9.26291 6.95598 9.16994 7.0497C8.98369 7.23707 8.87915 7.49052 8.87915 7.7547C8.87915 8.01889 8.98369 8.27234 9.16994 8.4597L12.7099 11.9997L9.16994 15.5397C8.98369 15.7271 8.87915 15.9805 8.87915 16.2447C8.87915 16.5089 8.98369 16.7623 9.16994 16.9497C9.26338 17.0424 9.3742 17.1157 9.49604 17.1655C9.61787 17.2152 9.74834 17.2405 9.87994 17.2397C10.0115 17.2405 10.142 17.2152 10.2638 17.1655C10.3857 17.1157 10.4965 17.0424 10.5899 16.9497L14.8299 12.7097C14.9237 12.6167 14.9981 12.5061 15.0488 12.3843C15.0996 12.2624 15.1257 12.1317 15.1257 11.9997C15.1257 11.8677 15.0996 11.737 15.0488 11.6151C14.9981 11.4933 14.9237 11.3827 14.8299 11.2897Z" fill="#3873A9"/>
                </svg>
              </div>
            @endif
          </div>
        @endif
      </div>
    </div>
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





<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>{{ $project->title }}</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
  <link rel="stylesheet" href="{{ asset('css/style.css') }}" />
</head>
<body>
  @include('components.header')

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

    $contentSource = trim((string) $project->content) !== ''
      ? (string) $project->content
      : (string) $project->description;

    $contentHtml = trim($contentSource) !== ''
      ? \Illuminate\Support\Str::markdown($contentSource)
      : '<p></p>';
  @endphp

  <main class="page">
    <div class="card-project">
      <div class="card__links">
        <a class="card__link" href="{{ route('projects.index') }}">Проекты</a>
        <div class="card__logo">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
            <path d="M12.3583 9.40874L8.82501 5.8754C8.74754 5.7973 8.65538 5.7353 8.55383 5.693C8.45228 5.65069 8.34336 5.62891 8.23335 5.62891C8.12334 5.62891 8.01442 5.65069 7.91287 5.693C7.81132 5.7353 7.71915 5.7973 7.64168 5.8754C7.48647 6.03154 7.39935 6.24275 7.39935 6.4629C7.39935 6.68306 7.48647 6.89427 7.64168 7.0504L10.5917 10.0004L7.64168 12.9504C7.48647 13.1065 7.39935 13.3178 7.39935 13.5379C7.39935 13.7581 7.48647 13.9693 7.64168 14.1254C7.71955 14.2026 7.81189 14.2637 7.91342 14.3052C8.01496 14.3467 8.12367 14.3677 8.23335 14.3671C8.34302 14.3677 8.45174 14.3467 8.55327 14.3052C8.6548 14.2637 8.74715 14.2026 8.82501 14.1254L12.3583 10.5921C12.4365 10.5146 12.4984 10.4224 12.5408 10.3209C12.5831 10.2193 12.6048 10.1104 12.6048 10.0004C12.6048 9.89039 12.5831 9.78147 12.5408 9.67992C12.4984 9.57837 12.4365 9.48621 12.3583 9.40874Z" fill="black"/>
          </svg>
        </div>
        <a class="card__link active" href="{{ route('projects.show', $project) }}">{{ $project->title }}</a>
      </div>

      <div class="card-project__content">
        <div class="card-project__image">
          <img src="{{ $mediaUrl($project->image, '/assets/6d77fe7c4a76248ddd7c01fd7a810ac36f81f17e.png') }}" alt="{{ $project->title }}">
        </div>
        <p class="card-project__name">{{ $project->title }}</p>
        <div class="card__editor">
          {!! $contentHtml !!}
        </div>
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

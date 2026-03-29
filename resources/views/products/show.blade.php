<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>{{ $product->title }}</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
  <link rel="stylesheet" href="{{ asset('css/style.css') }}" />
</head>
<body>@include('components.header')

  @php
    $product->loadMissing(['brand', 'category', 'subcategory.category', 'tabs']);

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

    $normalizeTabHtml = static function (string $content): string {
      $content = trim($content);

      if ($content === '') {
        return '<p></p>';
      }

      $html = \Illuminate\Support\Str::markdown($content);

      if (
        (! \Illuminate\Support\Str::contains($html, '<table'))
        && (! \Illuminate\Support\Str::contains($html, '<img'))
      ) {
        return $html;
      }

      $document = new \DOMDocument('1.0', 'UTF-8');
      $previousUseInternalErrors = libxml_use_internal_errors(true);
      $wrappedHtml = '<?xml encoding="utf-8" ?><div id="product-tab-root">' . $html . '</div>';
      $loaded = $document->loadHTML($wrappedHtml, \LIBXML_HTML_NOIMPLIED | \LIBXML_HTML_NODEFDTD);

      libxml_clear_errors();
      libxml_use_internal_errors($previousUseInternalErrors);

      if (! $loaded) {
        return $html;
      }

      $xpath = new \DOMXPath($document);
      $root = $xpath->query('//*[@id="product-tab-root"]')->item(0);

      if (! $root instanceof \DOMElement) {
        return $html;
      }

      $images = [];

      foreach ($xpath->query('.//img', $root) ?: [] as $imageNode) {
        if ($imageNode instanceof \DOMElement) {
          $images[] = $imageNode;
        }
      }

      foreach ($images as $imageNode) {
        $src = trim((string) $imageNode->getAttribute('src'));

        if ($src === '') {
          continue;
        }

        $isInsideTable = false;
        $linkNode = null;
        $ancestorNode = $imageNode->parentNode;

        while ($ancestorNode instanceof \DOMNode) {
          if ($ancestorNode instanceof \DOMElement) {
            if ($ancestorNode->tagName === 'a') {
              $linkNode = $ancestorNode;
            }

            if ($ancestorNode->tagName === 'table') {
              $isInsideTable = true;
              break;
            }
          }

          $ancestorNode = $ancestorNode->parentNode;
        }

        $imageClasses = preg_split('/\s+/', trim((string) $imageNode->getAttribute('class'))) ?: [];
        $imageClasses = array_values(array_filter($imageClasses));
        $imageClasses[] = 'card__editor-image';
        $imageClasses[] = $isInsideTable ? 'card__editor-image--table' : 'card__editor-image--standalone';
        $imageNode->setAttribute('class', implode(' ', array_unique($imageClasses)));

        if (! $linkNode instanceof \DOMElement) {
          $parentNode = $imageNode->parentNode;

          if (! $parentNode instanceof \DOMNode) {
            continue;
          }

          $linkNode = $document->createElement('a');
          $parentNode->replaceChild($linkNode, $imageNode);
          $linkNode->appendChild($imageNode);
        }

        $linkClasses = preg_split('/\s+/', trim((string) $linkNode->getAttribute('class'))) ?: [];
        $linkClasses = array_values(array_filter($linkClasses));
        $linkClasses[] = 'card__editor-image-link';
        $linkClasses[] = $isInsideTable ? 'card__editor-image-link--table' : 'card__editor-image-link--standalone';

        $linkNode->setAttribute('class', implode(' ', array_unique($linkClasses)));
        $linkNode->setAttribute('href', $src);
        $linkNode->setAttribute('target', '_blank');
        $linkNode->setAttribute('rel', 'noopener noreferrer');
      }

      $tables = [];

      foreach ($xpath->query('.//table', $root) ?: [] as $tableNode) {
        if ($tableNode instanceof \DOMElement) {
          $tables[] = $tableNode;
        }
      }

      foreach ($tables as $table) {
        $tableClasses = preg_split('/\s+/', trim((string) $table->getAttribute('class'))) ?: [];
        $tableClasses = array_values(array_filter($tableClasses));
        $hasSpecsTableClass = in_array('card__specs-table', $tableClasses, true);
        $wrapperClass = 'card__table-wrap';

        if ((! $hasSpecsTableClass) && (! in_array('card__editor-table', $tableClasses, true))) {
          $tableClasses[] = 'card__editor-table';
        }

        if ($hasSpecsTableClass) {
          $wrapperClass = 'card__specs';
        }

        if (! $hasSpecsTableClass) {
          $tableClasses = array_values(array_filter(
            $tableClasses,
            static fn (string $class): bool => ! preg_match('/^card__editor-table--/', $class),
          ));

          $rows = [];

          foreach ($xpath->query('.//tr', $table) ?: [] as $rowNode) {
            if ($rowNode instanceof \DOMElement) {
              $rows[] = $rowNode;
            }
          }

          $columnStats = [];

          foreach ($rows as $row) {
            $cells = [];

            foreach ($row->childNodes as $cellNode) {
              if (($cellNode instanceof \DOMElement) && in_array($cellNode->tagName, ['th', 'td'], true)) {
                $cells[] = $cellNode;
              }
            }

            foreach ($cells as $index => $cell) {
              $columnStats[$index] ??= [
                'bodyCells' => 0,
                'imageOnlyBodyCells' => 0,
                'headerLabels' => [],
              ];

              $cellText = trim(preg_replace('/\s+/u', ' ', $cell->textContent ?? ''));
              $imagesCount = 0;

              foreach ($xpath->query('.//img', $cell) ?: [] as $imageNode) {
                if ($imageNode instanceof \DOMElement) {
                  $imagesCount++;
                }
              }

              $isImageOnlyCell = ($imagesCount > 0) && ($cellText === '');

              if ($cell->tagName === 'th') {
                if ($cellText !== '') {
                  $columnStats[$index]['headerLabels'][] = mb_strtolower($cellText);
                }

                continue;
              }

              $columnStats[$index]['bodyCells']++;

              if ($isImageOnlyCell) {
                $columnStats[$index]['imageOnlyBodyCells']++;
              }
            }
          }

          $mediaColumns = [];

          foreach ($columnStats as $index => $stats) {
            $headerText = implode(' ', $stats['headerLabels']);
            $isMediaHeader = str_contains($headerText, 'фото')
              || str_contains($headerText, 'изображ')
              || str_contains($headerText, 'чертеж')
              || str_contains($headerText, 'чертёж');

            if (($stats['bodyCells'] > 0) && ($stats['imageOnlyBodyCells'] === $stats['bodyCells']) && $isMediaHeader) {
              $mediaColumns[] = $index;
            }
          }

          if ($mediaColumns !== []) {
            $tableClasses[] = 'card__editor-table--auto';

            foreach ($rows as $row) {
              $cells = [];

              foreach ($row->childNodes as $cellNode) {
                if (($cellNode instanceof \DOMElement) && in_array($cellNode->tagName, ['th', 'td'], true)) {
                  $cells[] = $cellNode;
                }
              }

              foreach ($mediaColumns as $columnIndex) {
                if (! isset($cells[$columnIndex])) {
                  continue;
                }

                $cell = $cells[$columnIndex];
                $cellClasses = preg_split('/\s+/', trim((string) $cell->getAttribute('class'))) ?: [];
                $cellClasses = array_values(array_filter($cellClasses));
                $cellClasses[] = 'card__editor-cell--media';
                $cell->setAttribute('class', implode(' ', array_unique($cellClasses)));
              }
            }
          }
        }

        $table->setAttribute('class', implode(' ', array_unique($tableClasses)));

        $parent = $table->parentNode;

        if (! $parent instanceof \DOMElement) {
          continue;
        }

        $parentClasses = preg_split('/\s+/', trim((string) $parent->getAttribute('class'))) ?: [];
        $parentClasses = array_values(array_filter($parentClasses));

        if (($parent->tagName === 'div') && in_array($wrapperClass, $parentClasses, true)) {
          continue;
        }

        $wrapper = $document->createElement('div');
        $wrapper->setAttribute('class', $wrapperClass);
        $parent->replaceChild($wrapper, $table);
        $wrapper->appendChild($table);
      }

      $normalizedHtml = '';

      foreach ($root->childNodes as $childNode) {
        $normalizedHtml .= $document->saveHTML($childNode);
      }

      return $normalizedHtml;
    };

    $tabs = collect();
    $usedTabKeys = [];

    foreach ($product->tabs as $tab) {
      $baseTabKey = \Illuminate\Support\Str::slug((string) $tab->title);

      if ($baseTabKey === '') {
        $baseTabKey = 'tab-' . $tab->id;
      }

      $tabKey = $baseTabKey;
      $tabSuffix = 1;

      while (isset($usedTabKeys[$tabKey])) {
        $tabKey = $baseTabKey . '-' . $tabSuffix;
        $tabSuffix++;
      }

      $usedTabKeys[$tabKey] = true;

      $tabs->push([
          'key' => $tabKey,
          'title' => $tab->title,
          'html' => $normalizeTabHtml((string) $tab->content),
      ]);
    }

    $categoryName = $product->category?->name
      ?? $product->subcategory?->category?->name
      ?? '';
  @endphp

  <main class="page">
    <section
      class="card"
      data-page="product-card"
      data-cart-product-id="{{ $product->id }}"
      data-cart-product-slug="{{ $product->slug }}"
      data-cart-product-title="{{ $product->title }}"
      data-cart-product-image="{{ $mediaUrl($product->image, '/assets/3e1e96143a03aff456fb2666bef78ffa11f15f5d.jpg') }}"
      data-cart-product-brand="{{ $product->brand?->name ?? '' }}"
      data-cart-product-category="{{ $categoryName }}"
    >
      <div class="card__links">
        <a class="card__link" href="{{ route('catalog.page') }}">Каталог</a>
        <div class="card__logo">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
            <path d="M12.3583 9.40874L8.82501 5.8754C8.74754 5.7973 8.65538 5.7353 8.55383 5.693C8.45228 5.65069 8.34336 5.62891 8.23335 5.62891C8.12334 5.62891 8.01442 5.65069 7.91287 5.693C7.81132 5.7353 7.71915 5.7973 7.64168 5.8754C7.48647 6.03154 7.39935 6.24275 7.39935 6.4629C7.39935 6.68306 7.48647 6.89427 7.64168 7.0504L10.5917 10.0004L7.64168 12.9504C7.48647 13.1065 7.39935 13.3178 7.39935 13.5379C7.39935 13.7581 7.48647 13.9693 7.64168 14.1254C7.71955 14.2026 7.81189 14.2637 7.91342 14.3052C8.01496 14.3467 8.12367 14.3677 8.23335 14.3671C8.34302 14.3677 8.45174 14.3467 8.55327 14.3052C8.6548 14.2637 8.74715 14.2026 8.82501 14.1254L12.3583 10.5921C12.4365 10.5146 12.4984 10.4224 12.5408 10.3209C12.5831 10.2193 12.6048 10.1104 12.6048 10.0004C12.6048 9.89039 12.5831 9.78147 12.5408 9.67992C12.4984 9.57837 12.4365 9.48621 12.3583 9.40874Z" fill="black"/>
          </svg>
        </div>
        <a class="card__link active" href="{{ route('products.show', $product->slug) }}">{{ $product->title }}</a>
      </div>

      <div class="card__main">
        <h1 class="card__title">{{ $product->title }}</h1>
        <div class="card__head">
          <div class="card__image">
            <a href="{{ $mediaUrl($product->image, '/assets/3e1e96143a03aff456fb2666bef78ffa11f15f5d.jpg') }}" target="_blank" rel="noopener noreferrer" class="card__image-link">
              <img src="{{ $mediaUrl($product->image, '/assets/3e1e96143a03aff456fb2666bef78ffa11f15f5d.jpg') }}" alt="{{ $product->title }}">
            </a>
          </div>
          <div class="card__params">
            <div class="card__param">
              <span class="card__param-name">Бренд</span>
              <span class="card__param-dots" aria-hidden="true"></span>
              <span class="card__param-value">{{ $product->brand?->name ?? '-' }}</span>
            </div>
            <div class="card__param">
              <span class="card__param-name">Категория</span>
              <span class="card__param-dots" aria-hidden="true"></span>
              <span class="card__param-value">{{ $categoryName !== '' ? $categoryName : '-' }}</span>
            </div>
          </div>
          <div class="card__price">
            <p class="card__price-title">
              Цена по запросу
            </p>
            <div class="card__price-btns">
              <div class="card__price-number" data-qty data-qty-min="1" data-qty-initial="1">
                <button class="card__price-item btn card__qty-btn card__qty-btn--minus is-disabled" type="button" data-qty-action="decrease" aria-label="Decrease quantity" aria-disabled="true">
                  <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                    <path d="M15.8334 9.16699H4.16671C3.94569 9.16699 3.73373 9.25479 3.57745 9.41107C3.42117 9.56735 3.33337 9.77931 3.33337 10.0003C3.33337 10.2213 3.42117 10.4333 3.57745 10.5896C3.73373 10.7459 3.94569 10.8337 4.16671 10.8337H15.8334C16.0544 10.8337 16.2663 10.7459 16.4226 10.5896C16.5789 10.4333 16.6667 10.2213 16.6667 10.0003C16.6667 9.77931 16.5789 9.56735 16.4226 9.41107C16.2663 9.25479 16.0544 9.16699 15.8334 9.16699Z" fill="#D6D6D9"/>
                  </svg>
                </button>
                <div class="card__price-item" data-qty-value>1</div>
                <button class="card__price-item btn card__qty-btn card__qty-btn--plus" type="button" data-qty-action="increase" aria-label="Increase quantity">
                  <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                    <path d="M15.8334 9.16634H10.8334V4.16634C10.8334 3.94533 10.7456 3.73337 10.5893 3.57709C10.433 3.42081 10.2211 3.33301 10 3.33301C9.77903 3.33301 9.56707 3.42081 9.41079 3.57709C9.2545 3.73337 9.16671 3.94533 9.16671 4.16634V9.16634H4.16671C3.94569 9.16634 3.73373 9.25414 3.57745 9.41042C3.42117 9.5667 3.33337 9.77866 3.33337 9.99967C3.33337 10.2207 3.42117 10.4326 3.57745 10.5889C3.73373 10.7452 3.94569 10.833 4.16671 10.833H9.16671V15.833C9.16671 16.054 9.2545 16.266 9.41079 16.4223C9.56707 16.5785 9.77903 16.6663 10 16.6663C10.2211 16.6663 10.433 16.5785 10.5893 16.4223C10.7456 16.266 10.8334 16.054 10.8334 15.833V10.833H15.8334C16.0544 10.833 16.2663 10.7452 16.4226 10.5889C16.5789 10.4326 16.6667 10.2207 16.6667 9.99967C16.6667 9.77866 16.5789 9.5667 16.4226 9.41042C16.2663 9.25414 16.0544 9.16634 15.8334 9.16634Z" fill="#3873A9"/>
                  </svg>
                </button>
              </div>
              <button class="btn btn__blue card__price-btn" type="button" data-add-to-cart>В счет</button>
            </div>
          </div>
        </div>
      </div>

      <div class="card__content">
        @if($tabs->isNotEmpty())
          <div class="catalog__switch card__content-switch">
            @foreach($tabs as $tab)
              <div class="catalog__switch-item {{ $loop->first ? 'active' : '' }}" data-card-tab="{{ $tab['key'] }}">
                {{ $tab['title'] }}
              </div>
              @if(! $loop->last)
                <div class="catalog__switch-line"></div>
              @endif
            @endforeach
          </div>
          <div class="card__editor">
            @foreach($tabs as $tab)
              <div class="card__editor-panel {{ $loop->first ? 'is-active' : '' }}" data-card-tab-panel="{{ $tab['key'] }}">
                {!! $tab['html'] !!}
              </div>
            @endforeach
          </div>
        @else
          <div class="card__editor">
            <div class="card__editor-panel is-active">
              <p>Информация отсутствует.</p>
            </div>
          </div>
        @endif
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

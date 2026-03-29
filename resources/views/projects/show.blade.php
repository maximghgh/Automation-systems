<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>{{ $project->title }}</title>
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

    $contentSource = trim((string) $project->content) !== ''
      ? (string) $project->content
      : (string) $project->description;

    $normalizeContentHtml = static function (string $content): string {
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
      $wrappedHtml = '<?xml encoding="utf-8" ?><div id="project-content-root">' . $html . '</div>';
      $loaded = $document->loadHTML($wrappedHtml, \LIBXML_HTML_NOIMPLIED | \LIBXML_HTML_NODEFDTD);

      libxml_clear_errors();
      libxml_use_internal_errors($previousUseInternalErrors);

      if (! $loaded) {
        return $html;
      }

      $xpath = new \DOMXPath($document);
      $root = $xpath->query('//*[@id="project-content-root"]')->item(0);

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

              foreach ($xpath->query('.//img', $cell) ?: [] as $innerImageNode) {
                if ($innerImageNode instanceof \DOMElement) {
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

    $contentHtml = trim($contentSource) !== ''
      ? $normalizeContentHtml($contentSource)
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
          <a href="{{ $mediaUrl($project->image, '/assets/6d77fe7c4a76248ddd7c01fd7a810ac36f81f17e.png') }}" target="_blank" rel="noopener noreferrer" class="card__image-link">
            <img src="{{ $mediaUrl($project->image, '/assets/6d77fe7c4a76248ddd7c01fd7a810ac36f81f17e.png') }}" alt="{{ $project->title }}">
          </a>
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

<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Каталог продукции</title>
  <link rel="stylesheet" href="{{ asset('css/style.css') }}" />
</head>
<body>@include('components.header')

  <main class="page">
    <section class="catalog">
      <h1 class="main__title">Каталог продукции</h1>
      <div class="catalog__container">
        <div class="catalog__filter">
          <div class="catalog__head">
            <p>Фильтр</p>
            <a href="#" data-catalog-reset>Сбросить</a>
          </div>
          <div class="catalog__switch">
            <div class="catalog__switch-item active" data-catalog-tab="brands">По брендам</div>
            <div class="catalog__switch-line"></div>
            <div class="catalog__switch-item" data-catalog-tab="categories">По категориям</div>
          </div>
          <div class="catalog__grid" data-catalog-brands></div>
          <div class="catalog__category" data-catalog-categories></div>
        </div>
        <div class="catalog__list">
          <div class="catalog__list-grid" data-catalog-products></div>
          <div class="catalog__pagination" data-catalog-pagination></div>
        </div>
      </div>
    </section>
  </main>

  @include('components.footer')

  @php
    $hasSubcategoriesTable = \Illuminate\Support\Facades\Schema::hasTable('subcategories');
    $hasProductsCategoryId = \Illuminate\Support\Facades\Schema::hasColumn('products', 'category_id');
    $hasProductsSubcategoryId = \Illuminate\Support\Facades\Schema::hasColumn('products', 'subcategory_id');

    $catalogBrands = \App\Models\Brand::query()
      ->orderBy('name')
      ->get(['id', 'name', 'image']);

    $categoriesQuery = \App\Models\Category::query()->orderBy('name');

    if ($hasSubcategoriesTable) {
      $categoriesQuery->with([
        'subcategories' => fn ($query) => $query
          ->orderBy('sort_order')
          ->orderBy('name')
          ->select(['id', 'category_id', 'name', 'sort_order']),
      ]);
    }

    $catalogCategories = $categoriesQuery->get(['id', 'name']);

    $productColumns = ['id', 'title', 'slug', 'image', 'short_description', 'brand_id', 'is_new'];

    if ($hasProductsCategoryId) {
      $productColumns[] = 'category_id';
    }

    if ($hasProductsSubcategoryId) {
      $productColumns[] = 'subcategory_id';
    }

    $catalogProductsQuery = \App\Models\Product::query()
      ->with(['brand:id,name'])
      ->orderByDesc('id');

    if ($hasProductsCategoryId) {
      $catalogProductsQuery->with('category:id,name');
    }

    if ($hasProductsSubcategoryId && $hasSubcategoriesTable) {
      $catalogProductsQuery->with('subcategory:id,category_id,name');

      if (! $hasProductsCategoryId) {
        $catalogProductsQuery->with('subcategory.category:id,name');
      }
    }

    $catalogProducts = $catalogProductsQuery->get($productColumns);

    $catalogBootstrapPayload = [
      'filters' => [
        'selected' => [
          'brands' => [],
          'categories' => [],
          'subcategories' => [],
        ],
        'brands' => $catalogBrands->map(fn ($brand) => [
          'id' => $brand->id,
          'name' => $brand->name,
          'image' => $brand->image,
        ])->values(),
        'categories' => $catalogCategories->map(function ($category) use ($hasSubcategoriesTable) {
          $subcategories = $hasSubcategoriesTable
            ? $category->subcategories->map(fn ($subcategory) => [
              'id' => $subcategory->id,
              'name' => $subcategory->name,
            ])->values()
            : collect();

          return [
            'id' => $category->id,
            'name' => $category->name,
            'subcategories' => $subcategories,
          ];
        })->values(),
      ],
      'products' => $catalogProducts->map(function ($product) use ($hasProductsCategoryId) {
        $subcategory = $product->relationLoaded('subcategory') ? $product->subcategory : null;
        $category = null;

        if ($hasProductsCategoryId && $product->relationLoaded('category') && $product->category) {
          $category = $product->category;
        } elseif ($subcategory && $subcategory->relationLoaded('category') && $subcategory->category) {
          $category = $subcategory->category;
        }

        return [
          'id' => $product->id,
          'title' => $product->title,
          'slug' => $product->slug,
          'image' => $product->image,
          'short_description' => $product->short_description,
          'is_new' => (bool) $product->is_new,
          'brand' => $product->brand ? [
            'id' => $product->brand->id,
            'name' => $product->brand->name,
          ] : null,
          'category' => $category ? [
            'id' => $category->id,
            'name' => $category->name,
          ] : null,
          'subcategory' => $subcategory ? [
            'id' => $subcategory->id,
            'name' => $subcategory->name,
          ] : null,
        ];
      })->values(),
    ];
  @endphp

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
      var endpoint = @json(route('catalog.index'));
      var productRouteTemplate = @json(route('products.show', ['product' => '__product__']));
      var preloadedCatalogPayload = @json($catalogBootstrapPayload);
      var fallbackBrandImage = "/assets/02cdaf796a3e94c7c7bfc4a8a987b9f69287b5af.png";
      var fallbackProductImage = "/assets/272582fd9c288dc352c6e9e18a1c2ebd46283542.png";

      var filterRoot = document.querySelector(".catalog__filter");
      var brandsRoot = document.querySelector("[data-catalog-brands]");
      var categoriesRoot = document.querySelector("[data-catalog-categories]");
      var productsRoot = document.querySelector("[data-catalog-products]");
      var paginationRoot = document.querySelector("[data-catalog-pagination]");
      var resetLink = document.querySelector("[data-catalog-reset]");
      var switchItems = Array.prototype.slice.call(document.querySelectorAll(".catalog__switch-item[data-catalog-tab]"));

      if (!filterRoot || !brandsRoot || !categoriesRoot || !productsRoot || !paginationRoot || !switchItems.length) {
        return;
      }

      var state = {
        tab: "brands",
        brands: new Set(),
        categories: new Set(),
        subcategories: new Set(),
        expandedCategories: new Set(),
        expandedSubcategories: new Set(),
        page: 1
      };

      var itemsPerPage = 12;
      var requestToken = 0;
      var cachedBrands = [];
      var cachedCategories = [];
      var cachedProducts = [];
      var cachedTreeProducts = [];
      var cachedProductsEmptyTitle = null;
      var cachedProductsEmptyText = null;

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

      function parseIdsFromQuery(params, key) {
        var rawValues = params.getAll(key);
        var ids = [];

        rawValues.forEach(function (rawValue) {
          String(rawValue)
            .split(",")
            .forEach(function (part) {
              var id = Number(String(part).trim());
              if (id > 0 && ids.indexOf(id) === -1) {
                ids.push(id);
              }
            });
        });

        return ids;
      }

      function setFromArray(values) {
        return new Set(
          (Array.isArray(values) ? values : [])
            .map(function (value) {
              return Number(value);
            })
            .filter(function (value) {
              return value > 0;
            })
        );
      }

      function setToSortedArray(set) {
        return Array.from(set).sort(function (a, b) {
          return a - b;
        });
      }

      function getArrowIconMarkup() {
        return '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none"><path d="M14.1666 7.64174C14.0104 7.48653 13.7992 7.39941 13.5791 7.39941C13.3589 7.39941 13.1477 7.48653 12.9916 7.64174L9.99992 10.5917L7.04992 7.64174C6.89378 7.48653 6.68257 7.39941 6.46242 7.39941C6.24226 7.39941 6.03105 7.48653 5.87492 7.64174C5.79681 7.71921 5.73481 7.81138 5.69251 7.91293C5.6502 8.01448 5.62842 8.1234 5.62842 8.23341C5.62842 8.34342 5.6502 8.45234 5.69251 8.55389C5.73481 8.65544 5.79681 8.74761 5.87492 8.82507L9.40825 12.3584C9.48572 12.4365 9.57789 12.4985 9.67944 12.5408C9.78099 12.5831 9.88991 12.6049 9.99992 12.6049C10.1099 12.6049 10.2188 12.5831 10.3204 12.5408C10.4219 12.4985 10.5141 12.4365 10.5916 12.3584L14.1666 8.82507C14.2447 8.74761 14.3067 8.65544 14.349 8.55389C14.3913 8.45234 14.4131 8.34342 14.4131 8.23341C14.4131 8.1234 14.3913 8.01448 14.349 7.91293C14.3067 7.81138 14.2447 7.71921 14.1666 7.64174Z" fill="#224565"/></svg>';
      }

      function buildProductsBySubcategory(products) {
        var grouped = {};
        (Array.isArray(products) ? products : []).forEach(function (product) {
          var subcategoryId = Number(product && product.subcategory && product.subcategory.id);
          var productId = Number(product && product.id);
          if (!(subcategoryId > 0) || !(productId > 0)) {
            return;
          }

          if (!grouped[subcategoryId]) {
            grouped[subcategoryId] = [];
          }

          if (grouped[subcategoryId].some(function (item) { return Number(item && item.id) === productId; })) {
            return;
          }

          grouped[subcategoryId].push({
            id: productId,
            title: typeof product.title === "string" ? product.title : "",
            slug: typeof product.slug === "string" ? product.slug : ""
          });
        });

        Object.keys(grouped).forEach(function (subcategoryId) {
          grouped[subcategoryId].sort(function (left, right) {
            return String(left.title || "").localeCompare(String(right.title || ""));
          });
        });

        return grouped;
      }

      function createEmptyState(title, text, modifierClass) {
        var box = document.createElement("div");
        box.className = "catalog__empty" + (modifierClass ? " " + modifierClass : "");

        var titleNode = document.createElement("p");
        titleNode.className = "catalog__empty-title";
        titleNode.textContent = title;

        var textNode = document.createElement("p");
        textNode.className = "catalog__empty-text";
        textNode.textContent = text;

        box.appendChild(titleNode);
        box.appendChild(textNode);

        return box;
      }

      function syncTabUi() {
        switchItems.forEach(function (item) {
          var isActive = item.getAttribute("data-catalog-tab") === state.tab;
          item.classList.toggle("active", isActive);
          item.setAttribute("aria-pressed", String(isActive));
        });

        brandsRoot.style.display = state.tab === "brands" ? "" : "none";
        categoriesRoot.style.display = state.tab === "categories" ? "" : "none";
      }

      function syncUrl() {
        var params = new URLSearchParams();
        var brandIds = setToSortedArray(state.brands);
        var categoryIds = setToSortedArray(state.categories);
        var subcategoryIds = setToSortedArray(state.subcategories);

        if (state.tab === "categories") {
          params.set("tab", "categories");
        }
        if (brandIds.length) {
          params.set("brands", brandIds.join(","));
        }
        if (categoryIds.length) {
          params.set("categories", categoryIds.join(","));
        }
        if (subcategoryIds.length) {
          params.set("subcategories", subcategoryIds.join(","));
        }
        if (state.page > 1) {
          params.set("page", String(state.page));
        }

        var nextUrl = window.location.pathname + (params.toString() ? "?" + params.toString() : "");
        window.history.replaceState(null, "", nextUrl);
      }

      function renderBrands(brands) {
        brandsRoot.innerHTML = "";
        cachedBrands = Array.isArray(brands) ? brands : [];

        if (!cachedBrands.length) {
          brandsRoot.appendChild(createEmptyState("Нет брендов", "Список брендов скоро появится.", "catalog__empty--filter"));
          return;
        }

        cachedBrands.forEach(function (brand) {
          var brandId = Number(brand && brand.id);
          var brandName = typeof brand.name === "string" && brand.name.trim().length ? brand.name.trim() : "Бренд";
          var image = normalizeMediaPath(brand.image) || fallbackBrandImage;

          var card = document.createElement("div");
          card.className = "catalog__brand";
          card.setAttribute("role", "button");
          card.setAttribute("tabindex", "0");
          card.setAttribute("data-brand-id", String(brandId));
          card.setAttribute("aria-pressed", String(state.brands.has(brandId)));
          card.classList.toggle("active", state.brands.has(brandId));

          var logo = document.createElement("div");
          logo.className = "catalog__brand-logo";

          var imageNode = document.createElement("img");
          imageNode.src = image;
          imageNode.alt = brandName;

          var nameNode = document.createElement("p");
          nameNode.className = "catalog__brand-name";
          nameNode.textContent = brandName;

          logo.appendChild(imageNode);
          card.appendChild(logo);
          card.appendChild(nameNode);
          brandsRoot.appendChild(card);
        });
      }

      function renderCategories(categories) {
        categoriesRoot.innerHTML = "";
        cachedCategories = Array.isArray(categories) ? categories : [];
        var productsBySubcategory = buildProductsBySubcategory(cachedTreeProducts);

        if (!cachedCategories.length) {
          categoriesRoot.appendChild(createEmptyState("Нет категорий", "Список категорий скоро появится.", "catalog__empty--filter"));
          return;
        }

        cachedCategories.forEach(function (category) {
          var categoryId = Number(category && category.id);
          var categoryName = typeof category.name === "string" && category.name.trim().length ? category.name.trim() : "Категория";
          var subcategories = Array.isArray(category.subcategories) ? category.subcategories : [];
          var hasSubcategories = subcategories.length > 0;
          var isExpanded = state.expandedCategories.has(categoryId);

          var container = document.createElement("div");
          container.className = "catalog__category-container";
          container.classList.toggle("is-expanded", isExpanded);
          container.setAttribute("data-category-container-id", String(categoryId));

          var item = document.createElement("div");
          item.className = "catalog__category-item";
          item.classList.toggle("active", state.categories.has(categoryId));
          item.setAttribute("role", "button");
          item.setAttribute("tabindex", "0");
          item.setAttribute("aria-pressed", String(state.categories.has(categoryId)));
          item.setAttribute("data-category-id", String(categoryId));

          var nameNode = document.createElement("p");
          nameNode.className = "catalog__category-name";
          nameNode.textContent = categoryName;

          var iconNode = document.createElement("div");
          iconNode.className = "catalog__category-icon";
          iconNode.classList.toggle("active", isExpanded);
          iconNode.innerHTML = getArrowIconMarkup();
          if (hasSubcategories) {
            iconNode.setAttribute("data-category-toggle", String(categoryId));
          }

          item.appendChild(nameNode);
          item.appendChild(iconNode);
          container.appendChild(item);

          if (hasSubcategories) {
            subcategories.forEach(function (subcategory) {
              var subcategoryId = Number(subcategory && subcategory.id);
              var subcategoryName = typeof subcategory.name === "string" && subcategory.name.trim().length ? subcategory.name.trim() : "Подкатегория";
              var subcategoryProducts = productsBySubcategory[subcategoryId] || [];
              var isSubcategoryExpanded = state.expandedSubcategories.has(subcategoryId);

              var subcategoryNode = document.createElement("div");
              subcategoryNode.className = "catalog__category-item catalog__category-sub";
              subcategoryNode.classList.toggle("active", state.subcategories.has(subcategoryId));
              subcategoryNode.setAttribute("role", "button");
              subcategoryNode.setAttribute("tabindex", "0");
              subcategoryNode.setAttribute("data-subcategory-id", String(subcategoryId));
              subcategoryNode.setAttribute("aria-pressed", String(state.subcategories.has(subcategoryId)));
              subcategoryNode.style.display = isExpanded ? "" : "none";

              var subcategoryNameNode = document.createElement("p");
              subcategoryNameNode.className = "catalog__category-name";
              subcategoryNameNode.textContent = subcategoryName;

              var subcategoryIconNode = document.createElement("div");
              subcategoryIconNode.className = "catalog__category-icon";
              subcategoryIconNode.classList.toggle("active", subcategoryProducts.length > 0 && isSubcategoryExpanded);
              subcategoryIconNode.innerHTML = getArrowIconMarkup();
              if (subcategoryProducts.length > 0) {
                subcategoryIconNode.setAttribute("data-subcategory-toggle", String(subcategoryId));
              }

              subcategoryNode.appendChild(subcategoryNameNode);
              subcategoryNode.appendChild(subcategoryIconNode);
              container.appendChild(subcategoryNode);

              if (!subcategoryProducts.length) {
                return;
              }

              var productsNode = document.createElement("div");
              productsNode.className = "catalog__category-prod";
              productsNode.style.display = isExpanded && isSubcategoryExpanded ? "" : "none";

              subcategoryProducts.forEach(function (subcategoryProduct) {
                var subcategoryProductTitle = typeof subcategoryProduct.title === "string" && subcategoryProduct.title.trim().length
                  ? subcategoryProduct.title.trim()
                  : "Product";
                var subcategoryProductSlug = typeof subcategoryProduct.slug === "string"
                  ? subcategoryProduct.slug.trim()
                  : "";
                var subcategoryProductUrl = subcategoryProductSlug.length
                  ? productRouteTemplate.replace("__product__", encodeURIComponent(subcategoryProductSlug))
                  : "#";

                var productLinkNode = document.createElement("a");
                productLinkNode.href = subcategoryProductUrl;
                productLinkNode.setAttribute("role", "button");
                productLinkNode.setAttribute("tabindex", "0");
                productLinkNode.textContent = subcategoryProductTitle;
                productsNode.appendChild(productLinkNode);
              });

              container.appendChild(productsNode);
            });
          }

          categoriesRoot.appendChild(container);
        });
      }

      function renderPagination(totalPages) {
        paginationRoot.innerHTML = "";
        paginationRoot.hidden = totalPages <= 1;

        if (totalPages <= 1) {
          return;
        }

        for (var page = 1; page <= totalPages; page += 1) {
          var pageNode = document.createElement("div");
          pageNode.className = "catalog__pagination-item";
          pageNode.classList.toggle("active", page === state.page);
          pageNode.textContent = String(page);
          pageNode.setAttribute("role", "button");
          pageNode.setAttribute("tabindex", "0");
          pageNode.setAttribute("data-page", String(page));
          pageNode.setAttribute("aria-current", page === state.page ? "page" : "false");
          paginationRoot.appendChild(pageNode);
        }
      }

      function renderProducts(products, emptyTitle, emptyText) {
        productsRoot.innerHTML = "";
        cachedProducts = Array.isArray(products) ? products : [];
        cachedProductsEmptyTitle = emptyTitle || null;
        cachedProductsEmptyText = emptyText || null;
        var list = cachedProducts;

        if (!list.length) {
          productsRoot.appendChild(
            createEmptyState(
              emptyTitle || "Нет товаров",
              emptyText || "Товары по выбранным фильтрам скоро появятся."
            )
          );
          renderPagination(0);
          return;
        }

        var totalPages = Math.max(1, Math.ceil(list.length / itemsPerPage));

        if (!(state.page > 0)) {
          state.page = 1;
        }

        if (state.page > totalPages) {
          state.page = totalPages;
        }

        var startIndex = (state.page - 1) * itemsPerPage;
        var pagedProducts = list.slice(startIndex, startIndex + itemsPerPage);

        pagedProducts.forEach(function (product) {
          var title = typeof product.title === "string" && product.title.trim().length ? product.title.trim() : "Товар";
          var description = "";
          if (typeof product.short_description === "string" && product.short_description.trim().length) {
            description = product.short_description.trim();
          } else if (typeof product.description === "string" && product.description.trim().length) {
            description = product.description.trim();
          }
          var image = normalizeMediaPath(product.image) || fallbackProductImage;
          var slug = typeof product.slug === "string" ? product.slug.trim() : "";
          var productUrl = slug.length ? productRouteTemplate.replace("__product__", encodeURIComponent(slug)) : "#";

          var item = document.createElement("div");
          item.className = "main-new__item catalog__list-item";

          var head = document.createElement("div");
          head.className = "catalog__list-head";

          var imageWrap = document.createElement("div");
          imageWrap.className = "main-new__img catalog__list-img";

          var imageNode = document.createElement("img");
          imageNode.src = image;
          imageNode.alt = title;

          var nameNode = document.createElement("p");
          nameNode.className = "main-new__name catalog__list-name";
          nameNode.textContent = title;

          var descriptionNode = null;
          if (description.length) {
            descriptionNode = document.createElement("p");
            descriptionNode.className = "main-projects__text catalog__list-text";
            descriptionNode.textContent = description;
          }

          var button = document.createElement("a");
          button.href = productUrl;
          button.className = "btn btn__blue main-new__btn";
          button.textContent = "Подробнее";

          imageWrap.appendChild(imageNode);
          head.appendChild(imageWrap);
          head.appendChild(nameNode);
          if (descriptionNode) {
            head.appendChild(descriptionNode);
          }
          item.appendChild(head);
          item.appendChild(button);
          productsRoot.appendChild(item);
        });

        renderPagination(totalPages);
      }

      function buildRequestUrl() {
        var url = new URL(endpoint, window.location.origin);
        var brandIds = setToSortedArray(state.brands);
        var categoryIds = setToSortedArray(state.categories);
        var subcategoryIds = setToSortedArray(state.subcategories);

        if (brandIds.length) {
          url.searchParams.set("brands", brandIds.join(","));
        }
        if (categoryIds.length) {
          url.searchParams.set("categories", categoryIds.join(","));
        }
        if (subcategoryIds.length) {
          url.searchParams.set("subcategories", subcategoryIds.join(","));
        }
        if (state.page > 1) {
          url.searchParams.set("page", String(state.page));
        }

        return url.toString();
      }

      function fetchCatalogData() {
        requestToken += 1;
        var currentToken = requestToken;
        if (preloadedCatalogPayload && preloadedCatalogPayload.filters) {
          var localFilters = preloadedCatalogPayload.filters || {};
          var localProducts = Array.isArray(preloadedCatalogPayload.products) ? preloadedCatalogPayload.products : [];
          var filteredProducts = localProducts.filter(function (product) {
            var brandId = Number(product && product.brand && product.brand.id);
            var categoryId = Number(product && product.category && product.category.id);
            var subcategoryId = Number(product && product.subcategory && product.subcategory.id);
            if (state.brands.size && !state.brands.has(brandId)) {
              return false;
            }
            if (state.categories.size && !state.categories.has(categoryId)) {
              return false;
            }
            if (state.subcategories.size && !state.subcategories.has(subcategoryId)) {
              return false;
            }
            return true;
          });
          cachedTreeProducts = localProducts;
          cachedProducts = filteredProducts;
          renderBrands(localFilters.brands || []);
          renderCategories(localFilters.categories || []);
          renderProducts(filteredProducts);
          syncTabUi();
          syncUrl();
          return;
        }
        fetch(buildRequestUrl(), {
          headers: {
            "Accept": "application/json"
          }
        })
          .then(function (response) {
            if (!response.ok) {
              throw new Error("Catalog request failed");
            }
            return response.json();
          })
          .then(function (payload) {
            if (currentToken !== requestToken) {
              return;
            }
            var filters = payload && payload.filters ? payload.filters : {};
            var selected = filters.selected || {};
            state.brands = setFromArray(selected.brands);
            state.categories = setFromArray(selected.categories);
            state.subcategories = setFromArray(selected.subcategories);
            cachedTreeProducts = Array.isArray(payload && payload.products) ? payload.products : [];
            cachedProducts = Array.isArray(payload && payload.products) ? payload.products : [];
            renderBrands(filters.brands || []);
            renderCategories(filters.categories || []);
            renderProducts(cachedProducts);
            syncTabUi();
            syncUrl();
          })
          .catch(function () {
            if (currentToken !== requestToken) {
              return;
            }
            cachedTreeProducts = [];
            cachedProducts = [];
            renderProducts([], "Catalog load failed", "Please refresh and try again.");
            renderBrands([]);
            renderCategories([]);
            syncTabUi();
          });
      }
      function toggleSetValue(set, value) {
        if (set.has(value)) {
          set.delete(value);
          return;
        }
        set.add(value);
      }

      function handleBrandToggle(brandNode) {
        var brandId = Number(brandNode.getAttribute("data-brand-id"));
        if (!(brandId > 0)) {
          return;
        }

        toggleSetValue(state.brands, brandId);
        state.page = 1;
        fetchCatalogData();
      }

      function handleCategoryToggle(categoryNode) {
        var categoryId = Number(categoryNode.getAttribute("data-category-id"));
        if (!(categoryId > 0)) {
          return;
        }

        toggleSetValue(state.categories, categoryId);
        state.page = 1;
        fetchCatalogData();
      }

      function handleSubcategoryToggle(subcategoryNode) {
        var subcategoryId = Number(subcategoryNode.getAttribute("data-subcategory-id"));
        if (!(subcategoryId > 0)) {
          return;
        }

        toggleSetValue(state.subcategories, subcategoryId);
        state.page = 1;
        fetchCatalogData();
      }

      function handleExpandToggle(toggleNode) {
        var categoryId = Number(toggleNode.getAttribute("data-category-toggle"));
        if (!(categoryId > 0)) {
          return;
        }

        toggleSetValue(state.expandedCategories, categoryId);
        renderCategories(cachedCategories);
        syncUrl();
      }

      function handleSubcategoryExpandToggle(toggleNode) {
        var subcategoryId = Number(toggleNode.getAttribute("data-subcategory-toggle"));
        if (!(subcategoryId > 0)) {
          return;
        }

        toggleSetValue(state.expandedSubcategories, subcategoryId);
        renderCategories(cachedCategories);
        syncUrl();
      }

      function handleKeyboardAction(event, handler) {
        if (event.key !== "Enter" && event.key !== " ") {
          return;
        }

        event.preventDefault();
        handler();
      }

      switchItems.forEach(function (item) {
        item.setAttribute("role", "button");
        item.setAttribute("tabindex", "0");
        item.setAttribute("aria-pressed", String(item.classList.contains("active")));

        var tabName = item.getAttribute("data-catalog-tab");
        function activateTab() {
          state.tab = tabName === "categories" ? "categories" : "brands";
          syncTabUi();
          syncUrl();
        }

        item.addEventListener("click", activateTab);
        item.addEventListener("keydown", function (event) {
          handleKeyboardAction(event, activateTab);
        });
      });

      brandsRoot.addEventListener("click", function (event) {
        var brandNode = event.target.closest("[data-brand-id]");
        if (!brandNode) {
          return;
        }

        handleBrandToggle(brandNode);
      });

      brandsRoot.addEventListener("keydown", function (event) {
        var brandNode = event.target.closest("[data-brand-id]");
        if (!brandNode) {
          return;
        }

        handleKeyboardAction(event, function () {
          handleBrandToggle(brandNode);
        });
      });

      categoriesRoot.addEventListener("click", function (event) {
        var subcategoryToggleNode = event.target.closest("[data-subcategory-toggle]");
        if (subcategoryToggleNode) {
          event.preventDefault();
          event.stopPropagation();
          handleSubcategoryExpandToggle(subcategoryToggleNode);
          return;
        }

        var categoryToggleNode = event.target.closest("[data-category-toggle]");
        if (categoryToggleNode) {
          event.preventDefault();
          event.stopPropagation();
          handleExpandToggle(categoryToggleNode);
          return;
        }

        var subcategoryNode = event.target.closest("[data-subcategory-id]");
        if (subcategoryNode) {
          event.preventDefault();
          handleSubcategoryToggle(subcategoryNode);
          return;
        }

        var categoryNode = event.target.closest("[data-category-id]");
        if (!categoryNode) {
          return;
        }

        handleCategoryToggle(categoryNode);
      });

      categoriesRoot.addEventListener("keydown", function (event) {
        var subcategoryToggleNode = event.target.closest("[data-subcategory-toggle]");
        if (subcategoryToggleNode) {
          handleKeyboardAction(event, function () {
            handleSubcategoryExpandToggle(subcategoryToggleNode);
          });
          return;
        }

        var categoryToggleNode = event.target.closest("[data-category-toggle]");
        if (categoryToggleNode) {
          handleKeyboardAction(event, function () {
            handleExpandToggle(categoryToggleNode);
          });
          return;
        }

        var subcategoryNode = event.target.closest("[data-subcategory-id]");
        if (subcategoryNode) {
          handleKeyboardAction(event, function () {
            handleSubcategoryToggle(subcategoryNode);
          });
          return;
        }

        var categoryNode = event.target.closest("[data-category-id]");
        if (!categoryNode) {
          return;
        }

        handleKeyboardAction(event, function () {
          handleCategoryToggle(categoryNode);
        });
      });

      function handlePageChange(pageNode) {
        var nextPage = Number(pageNode && pageNode.getAttribute("data-page"));

        if (!(nextPage > 0) || nextPage === state.page) {
          return;
        }

        state.page = nextPage;
        renderProducts(cachedProducts, cachedProductsEmptyTitle, cachedProductsEmptyText);
        syncUrl();
      }

      paginationRoot.addEventListener("click", function (event) {
        var pageNode = event.target.closest("[data-page]");
        if (!pageNode) {
          return;
        }

        handlePageChange(pageNode);
      });

      paginationRoot.addEventListener("keydown", function (event) {
        var pageNode = event.target.closest("[data-page]");
        if (!pageNode) {
          return;
        }

        handleKeyboardAction(event, function () {
          handlePageChange(pageNode);
        });
      });

      if (resetLink) {
        resetLink.addEventListener("click", function (event) {
          event.preventDefault();
          state.tab = "brands";
          state.brands.clear();
          state.categories.clear();
          state.subcategories.clear();
          state.expandedCategories.clear();
          state.expandedSubcategories.clear();
          state.page = 1;
          syncTabUi();
          fetchCatalogData();
        });
      }

      (function initFromQuery() {
        var params = new URLSearchParams(window.location.search);
        state.tab = params.get("tab") === "categories" ? "categories" : "brands";
        state.brands = setFromArray(parseIdsFromQuery(params, "brands"));
        state.categories = setFromArray(parseIdsFromQuery(params, "categories"));
        state.subcategories = setFromArray(parseIdsFromQuery(params, "subcategories"));
        state.page = Math.max(1, Number(params.get("page")) || 1);

        if (state.categories.size || state.subcategories.size) {
          state.tab = "categories";
        }

        syncTabUi();
        fetchCatalogData();
      })();
    })();
  </script>
</body>
</html>

(function () {
  var section = document.querySelector(".main-about_us");
  if (!section || typeof Swiper === "undefined") {
    return;
  }

  var sliderRoot = section.querySelector(".main-about_us__slider-swiper");
  var prevButton = section.querySelector(".main-about_us__prev");
  var nextButton = section.querySelector(".main-about_us__next");
  var currentNode = section.querySelector(".main-about_us__number > span:not(.main-about_us__number-total)");
  var totalNode = section.querySelector(".main-about_us__number-total");
  var subtitleNode = section.querySelector(".main-about_us__subtitle");
  var textNode = section.querySelector(".main-about_us__text");

  if (!sliderRoot || !prevButton || !nextButton || !subtitleNode || !textNode) {
    return;
  }

  var initialSubtitle = subtitleNode.textContent;
  var initialText = textNode.textContent;
  var previousIndex = 0;
  var currentIndex = 0;
  var slidesData = [];

  function formatIndex(value) {
    return String(value).padStart(2, "0");
  }

  function getSlidesOffsetAfter() {
    var viewportWidth = window.innerWidth;
    if (viewportWidth <= 575.98) {
      return 30;
    }
    if (viewportWidth <= 991.98) {
      return 50;
    }
    return Math.max(80, (viewportWidth - 1280) / 2);
  }

  function getSlidesOffsetBefore() {
    var viewportWidth = window.innerWidth;
    if (viewportWidth <= 991.98) {
      return 0;
    }
    return 65;
  }

  function animateText(node, value, direction, withAnimation) {
    if (!node) {
      return;
    }

    if (!withAnimation) {
      node.textContent = value;
      return;
    }

    var animationClass = direction >= 0 ? "is-slide-up" : "is-slide-down";
    node.classList.remove("is-slide-up", "is-slide-down");
    void node.offsetWidth;
    node.textContent = value;
    node.classList.add(animationClass);

    if (node._aboutAnimTimer) {
      window.clearTimeout(node._aboutAnimTimer);
    }

    node._aboutAnimTimer = window.setTimeout(function () {
      node.classList.remove("is-slide-up", "is-slide-down");
    }, 420);
  }

  function clampIndex(index) {
    return Math.max(0, Math.min(index, slidesCount - 1));
  }

  function getIndexFromSwiper(instance) {
    var index = instance.activeIndex;
    if (instance.isEnd) {
      index = slidesCount - 1;
    }
    return clampIndex(index);
  }

  function updateCopyByIndex(index, withAnimation) {
    if (!slidesData.length) {
      return;
    }

    var displayIndex = clampIndex(index);
    var activeData = slidesData[displayIndex];
    if (!activeData) {
      return;
    }

    var subtitle = activeData.subtitle || initialSubtitle;
    var text = activeData.text || initialText;
    var direction = displayIndex >= previousIndex ? 1 : -1;

    if (currentNode) {
      currentNode.textContent = formatIndex(displayIndex + 1);
    }

    animateText(subtitleNode, subtitle, direction, withAnimation);
    animateText(textNode, text, direction, withAnimation);

    currentIndex = displayIndex;
    previousIndex = displayIndex;
  }

  function updateNavState() {
    prevButton.classList.toggle("swiper-button-disabled", currentIndex <= 0);
    nextButton.classList.toggle("swiper-button-disabled", currentIndex >= slidesCount - 1);
  }

  function syncFromSwiper(instance, withAnimation) {
    var index = getIndexFromSwiper(instance);
    if (index !== currentIndex || !withAnimation) {
      updateCopyByIndex(index, withAnimation);
    }
    updateNavState();
  }

  slidesData = Array.from(sliderRoot.querySelectorAll(".main-about_us__slide")).map(function (slide) {
    return {
      subtitle: slide.getAttribute("data-subtitle") || "",
      text: slide.getAttribute("data-text") || "",
    };
  });

  var slidesCount = slidesData.length;
  if (totalNode) {
    totalNode.textContent = formatIndex(slidesCount);
  }

  var aboutSwiper = new Swiper(sliderRoot, {
    initialSlide: 0,
    slidesPerView: "auto",
    spaceBetween: 22,
    speed: 420,
    grabCursor: true,
    roundLengths: true,
    watchOverflow: true,
    slidesOffsetBefore: getSlidesOffsetBefore(),
    slidesOffsetAfter: getSlidesOffsetAfter(),
    breakpoints: {
      0: {
        spaceBetween: 12,
      },
      576: {
        spaceBetween: 18,
      },
      992: {
        spaceBetween: 22,
      },
    },
    on: {
      init: function () {
        syncFromSwiper(this, false);
      },
      slideChangeTransitionStart: function () {
        syncFromSwiper(this, true);
      },
      reachEnd: function () {
        updateCopyByIndex(slidesCount - 1, true);
        updateNavState();
      },
      fromEdge: function () {
        syncFromSwiper(this, true);
      },
      resize: function () {
        this.params.slidesOffsetBefore = getSlidesOffsetBefore();
        this.params.slidesOffsetAfter = getSlidesOffsetAfter();
        this.update();
        this.slideTo(clampIndex(currentIndex), 0);
        syncFromSwiper(this, false);
      },
    },
  });

  if (!aboutSwiper || !slidesCount) {
    return;
  }

  prevButton.addEventListener("click", function (event) {
    event.preventDefault();
    if (slidesCount <= 1) {
      return;
    }

    var targetIndex = clampIndex(currentIndex - 1);
    if (targetIndex === currentIndex) {
      return;
    }

    aboutSwiper.slideTo(targetIndex);
    updateCopyByIndex(targetIndex, true);
    updateNavState();
  });

  nextButton.addEventListener("click", function (event) {
    event.preventDefault();
    if (slidesCount <= 1) {
      return;
    }

    var targetIndex = clampIndex(currentIndex + 1);
    if (targetIndex === currentIndex) {
      return;
    }

    aboutSwiper.slideTo(targetIndex);
    updateCopyByIndex(targetIndex, true);
    updateNavState();
  });
})();

(function () {
  var section = document.querySelector(".main-new");
  if (!section || typeof Swiper === "undefined") {
    return;
  }

  var sliderRoot = section.querySelector(".main-new__slider-swiper");
  var prevButton = section.querySelector(".main-new__prev");
  var nextButton = section.querySelector(".main-new__next");
  if (!sliderRoot) {
    return;
  }

  var slidesCount = sliderRoot.querySelectorAll(".swiper-slide").length;
  var config = {
    loop: slidesCount > 1,
    loopAddBlankSlides: true,
    speed: 420,
    spaceBetween: 20,
    slidesPerView: 1,
    slidesPerGroup: 1,
    grabCursor: true,
    roundLengths: true,
    allowTouchMove: true,
    watchOverflow: false,
    breakpoints: {
      768: {
        slidesPerView: 2,
      },
      1200: {
        slidesPerView: 3,
      },
    },
  };

  if (prevButton && nextButton) {
    config.navigation = {
      prevEl: prevButton,
      nextEl: nextButton,
    };
  }

  new Swiper(sliderRoot, config);
})();

(function () {
  var section = document.querySelector(".main-projects");
  if (!section || typeof Swiper === "undefined") {
    return;
  }

  var grid = section.querySelector(".main-projects__grid");
  var prevButton = section.querySelector(".main-new__prev");
  var nextButton = section.querySelector(".main-new__next");
  if (!grid) {
    return;
  }

  var sliderRoot = section.querySelector(".main-projects__slider-swiper");
  if (!sliderRoot) {
    sliderRoot = document.createElement("div");
    sliderRoot.className = "main-projects__slider-swiper swiper";
    grid.parentNode.insertBefore(sliderRoot, grid);
    sliderRoot.appendChild(grid);
  }

  grid.classList.add("swiper-wrapper");

  var baseSlides = Array.from(grid.querySelectorAll(".main-projects__item"));
  if (!baseSlides.length) {
    return;
  }

  baseSlides.forEach(function (slide) {
    slide.classList.add("swiper-slide");
  });

  var slidesCount = grid.querySelectorAll(".main-projects__item").length;
  var config = {
    loop: slidesCount > 4,
    speed: 420,
    spaceBetween: 20,
    slidesPerView: 1,
    slidesPerGroup: 1,
    grabCursor: true,
    roundLengths: true,
    allowTouchMove: true,
    watchOverflow: false,
    breakpoints: {
      576: {
        slidesPerView: 2,
      },
      992: {
        slidesPerView: 3,
      },
      1200: {
        slidesPerView: 4,
      },
    },
  };

  if (prevButton && nextButton) {
    config.navigation = {
      prevEl: prevButton,
      nextEl: nextButton,
    };
  }

  new Swiper(sliderRoot, config);
})();

(function () {
  var forms = Array.from(document.querySelectorAll("[data-header-search-form]"));
  if (!forms.length || typeof window.fetch !== "function" || typeof DOMParser === "undefined") {
    return;
  }

  var MIN_QUERY_LENGTH = 2;
  var DEBOUNCE_DELAY = 220;
  var searchCache = {};

  function escapeHtml(value) {
    return String(value)
      .replace(/&/g, "&amp;")
      .replace(/</g, "&lt;")
      .replace(/>/g, "&gt;")
      .replace(/"/g, "&quot;")
      .replace(/'/g, "&#39;");
  }

  function normalizeWhitespace(value) {
    return String(value || "").replace(/\s+/g, " ").trim();
  }

  function detectResultType(url, fallbackType) {
    var normalizedUrl = String(url || "").toLowerCase();

    if (normalizedUrl.indexOf("/services/") > -1) {
      return "\u0423\u0441\u043b\u0443\u0433\u0430";
    }
    if (normalizedUrl.indexOf("/projects/") > -1) {
      return "\u041f\u0440\u043e\u0435\u043a\u0442";
    }
    if (normalizedUrl.indexOf("/catalog/") > -1 || normalizedUrl.indexOf("/products/") > -1) {
      return "\u0422\u043e\u0432\u0430\u0440";
    }

    var cleanedFallback = normalizeWhitespace(fallbackType);
    if (cleanedFallback.length) {
      return cleanedFallback;
    }

    return "\u0420\u0435\u0437\u0443\u043b\u044c\u0442\u0430\u0442";
  }

  function buildSearchUrl(action, query) {
    var target = action || "/search";

    try {
      var absolute = new URL(target, window.location.origin);
      absolute.searchParams.set("q", query);
      return absolute.toString();
    } catch (_error) {
      return "/search?q=" + encodeURIComponent(query);
    }
  }

  function parseResultsFromHtml(html) {
    var documentFromResponse = new DOMParser().parseFromString(html, "text/html");
    var nodes = Array.from(documentFromResponse.querySelectorAll("[data-search-result-link]"));

    return nodes.map(function (node) {
      var href = node.getAttribute("href") || "";
      var title = normalizeWhitespace(node.getAttribute("data-search-result-title") || node.textContent || "");
      var type = detectResultType(href, node.getAttribute("data-search-result-type") || "");

      if (!href || !title) {
        return null;
      }

      return {
        href: href,
        title: title,
        type: type
      };
    }).filter(Boolean);
  }

  function buildStateMarkup(message, stateClass) {
    return [
      '<div class="header__search-popup-state ',
      stateClass || "",
      '">',
      escapeHtml(message),
      "</div>"
    ].join("");
  }

  function buildMoreLinkMarkup(url) {
    return [
      '<div class="header__search-popup-footer">',
      '<a class="header__search-popup-more" href="',
      escapeHtml(url),
      '">\u041f\u043e\u043a\u0430\u0437\u0430\u0442\u044c \u0432\u0441\u0435 \u0440\u0435\u0437\u0443\u043b\u044c\u0442\u0430\u0442\u044b</a>',
      "</div>"
    ].join("");
  }

  function buildResultsMarkup(results, searchUrl) {
    if (!results.length) {
      return [
        buildStateMarkup("\u041d\u0438\u0447\u0435\u0433\u043e \u043d\u0435 \u043d\u0430\u0439\u0434\u0435\u043d\u043e", "is-empty"),
        buildMoreLinkMarkup(searchUrl)
      ].join("");
    }

    var linksMarkup = results.slice(0, 8).map(function (item) {
      return [
        '<a class="header__search-popup-link" href="',
        escapeHtml(item.href),
        '">',
        '<span class="header__search-popup-title">',
        escapeHtml(item.title),
        "</span>",
        '<span class="header__search-popup-type">',
        escapeHtml(item.type),
        "</span>",
        "</a>"
      ].join("");
    }).join("");

    return [
      '<div class="header__search-popup-list">',
      linksMarkup,
      "</div>",
      buildMoreLinkMarkup(searchUrl)
    ].join("");
  }

  function closePopup(form, popup) {
    popup.hidden = true;
    form.classList.remove("is-search-open");
  }

  function closeAllPopups() {
    forms.forEach(function (form) {
      var popup = form.querySelector("[data-header-search-popup]");
      if (!popup) {
        return;
      }
      closePopup(form, popup);
    });
  }

  forms.forEach(function (form) {
    var input = form.querySelector("[data-header-search-input]");
    var popup = form.querySelector("[data-header-search-popup]");
    var resultsRoot = form.querySelector("[data-header-search-results]");
    var clearIcon = form.querySelector(".header__search-icon--clear");
    if (!input || !popup || !resultsRoot) {
      return;
    }

    var timerId = null;
    var activeRequest = null;
    var requestNonce = 0;

    function openPopup() {
      popup.hidden = false;
      form.classList.add("is-search-open");
    }

    function renderMarkup(markup) {
      resultsRoot.innerHTML = markup;
      openPopup();
    }

    function renderHint() {
      renderMarkup(buildStateMarkup("\u0412\u0432\u0435\u0434\u0438\u0442\u0435 \u043c\u0438\u043d\u0438\u043c\u0443\u043c 2 \u0441\u0438\u043c\u0432\u043e\u043b\u0430", "is-hint"));
    }

    function renderLoading() {
      renderMarkup(buildStateMarkup("\u0418\u0449\u0435\u043c \u043f\u043e \u0441\u0430\u0439\u0442\u0443...", "is-loading"));
    }

    function renderError() {
      renderMarkup(buildStateMarkup("\u041d\u0435 \u0443\u0434\u0430\u043b\u043e\u0441\u044c \u0437\u0430\u0433\u0440\u0443\u0437\u0438\u0442\u044c \u0440\u0435\u0437\u0443\u043b\u044c\u0442\u0430\u0442\u044b", "is-error"));
    }

    function stopPendingSearch() {
      if (timerId) {
        window.clearTimeout(timerId);
        timerId = null;
      }

      if (activeRequest) {
        activeRequest.abort();
        activeRequest = null;
      }
    }

    function handleQueryChange() {
      var query = normalizeWhitespace(input.value);
      stopPendingSearch();

      if (!query.length) {
        closePopup(form, popup);
        return;
      }

      closeAllPopups();
      openPopup();

      if (query.length < MIN_QUERY_LENGTH) {
        renderHint();
        return;
      }

      var searchUrl = buildSearchUrl(form.getAttribute("action"), query);
      var cacheKey = query.toLowerCase();
      if (searchCache[cacheKey]) {
        renderMarkup(buildResultsMarkup(searchCache[cacheKey], searchUrl));
        return;
      }

      timerId = window.setTimeout(function () {
        var thisNonce = requestNonce + 1;
        requestNonce = thisNonce;
        renderLoading();

        activeRequest = new AbortController();
        fetch(searchUrl, {
          headers: {
            Accept: "text/html",
            "X-Requested-With": "XMLHttpRequest"
          },
          signal: activeRequest.signal
        })
          .then(function (response) {
            if (!response.ok) {
              throw new Error("Search request failed");
            }
            return response.text();
          })
          .then(function (html) {
            if (requestNonce !== thisNonce) {
              return;
            }

            var parsedResults = parseResultsFromHtml(html);
            searchCache[cacheKey] = parsedResults;
            renderMarkup(buildResultsMarkup(parsedResults, searchUrl));
          })
          .catch(function (error) {
            if (error && error.name === "AbortError") {
              return;
            }
            if (requestNonce !== thisNonce) {
              return;
            }
            renderError();
          });
      }, DEBOUNCE_DELAY);
    }

    input.addEventListener("focus", handleQueryChange);
    input.addEventListener("input", handleQueryChange);

    form.addEventListener("submit", function (event) {
      var query = normalizeWhitespace(input.value);
      if (query.length >= MIN_QUERY_LENGTH) {
        return;
      }

      event.preventDefault();
      if (!query.length) {
        closePopup(form, popup);
        return;
      }
      renderHint();
    });

    if (clearIcon) {
      clearIcon.addEventListener("click", function () {
        input.value = "";
        stopPendingSearch();
        closePopup(form, popup);
      });

      clearIcon.addEventListener("keydown", function (event) {
        if (event.key !== "Enter" && event.key !== " ") {
          return;
        }
        event.preventDefault();
        input.value = "";
        stopPendingSearch();
        closePopup(form, popup);
      });
    }

    resultsRoot.addEventListener("click", function (event) {
      var link = event.target.closest(".header__search-popup-link, .header__search-popup-more");
      if (!link) {
        return;
      }
      closePopup(form, popup);
    });
  });

  document.addEventListener("click", function (event) {
    if (event.target.closest("[data-header-search-form]")) {
      return;
    }
    closeAllPopups();
  });

  document.addEventListener("keydown", function (event) {
    if (event.key !== "Escape") {
      return;
    }
    closeAllPopups();
  });
})();

(function () {
  var card = document.querySelector(".card");
  if (!card) {
    return;
  }

  var qtyRoot = card.querySelector("[data-qty]");
  if (qtyRoot) {
    var minusButton = qtyRoot.querySelector("[data-qty-action='decrease']");
    var plusButton = qtyRoot.querySelector("[data-qty-action='increase']");
    var valueNode = qtyRoot.querySelector("[data-qty-value]");
    var min = parseInt(qtyRoot.getAttribute("data-qty-min"), 10);
    var initial = parseInt(qtyRoot.getAttribute("data-qty-initial"), 10);
    var initialFromValue = valueNode ? parseInt(valueNode.textContent, 10) : NaN;
    if (!Number.isFinite(initial)) {
      initial = initialFromValue;
    }
    var current = Number.isFinite(initial) ? initial : 1;

    if (!Number.isFinite(min)) {
      min = 1;
    }

    function syncCurrentFromNode() {
      if (!valueNode) {
        return;
      }

      var domValue = parseInt(valueNode.textContent, 10);
      if (Number.isFinite(domValue)) {
        current = domValue;
      }
    }

    function syncMinusState() {
      syncCurrentFromNode();
      var isDisabled = current <= min;
      if (minusButton) {
        minusButton.classList.toggle("is-disabled", isDisabled);
        minusButton.setAttribute("aria-disabled", isDisabled ? "true" : "false");
      }
    }

    function renderQuantity(nextValue) {
      current = Math.max(min, nextValue);
      if (valueNode) {
        valueNode.textContent = String(current);
      }
      syncMinusState();
    }

    renderQuantity(current);

    if (minusButton) {
      minusButton.addEventListener("click", function () {
        syncCurrentFromNode();
        if (current <= min) {
          return;
        }
        renderQuantity(current - 1);
      });
    }

    if (plusButton) {
      plusButton.addEventListener("click", function () {
        syncCurrentFromNode();
        renderQuantity(current + 1);
      });
    }
  }

  var tabButtons = Array.from(card.querySelectorAll(".card__content-switch [data-card-tab]"));
  var tabPanels = Array.from(card.querySelectorAll("[data-card-tab-panel]"));

  if (!tabButtons.length || !tabPanels.length) {
    return;
  }

  function activateTab(tabName) {
    var hasPanel = false;

    tabPanels.forEach(function (panel) {
      var isActive = panel.getAttribute("data-card-tab-panel") === tabName;
      panel.classList.toggle("is-active", isActive);
      if (isActive) {
        hasPanel = true;
      }
    });

    if (!hasPanel) {
      return;
    }

    tabButtons.forEach(function (button) {
      var isActive = button.getAttribute("data-card-tab") === tabName;
      button.classList.toggle("active", isActive);
    });
  }

  tabButtons.forEach(function (button) {
    button.setAttribute("tabindex", "0");
    button.addEventListener("click", function () {
      activateTab(button.getAttribute("data-card-tab"));
    });
    button.addEventListener("keydown", function (event) {
      if (event.key === "Enter" || event.key === " ") {
        event.preventDefault();
        activateTab(button.getAttribute("data-card-tab"));
      }
    });
  });

  var activeButton = card.querySelector(".card__content-switch .catalog__switch-item.active[data-card-tab]");
  activateTab(activeButton ? activeButton.getAttribute("data-card-tab") : tabButtons[0].getAttribute("data-card-tab"));
})();

(function () {
  var basket = document.querySelector(".basket");
  if (!basket) {
    return;
  }

  var nextButton = basket.querySelector("[data-basket-next-btn]");
  var stepLinks = Array.from(basket.querySelectorAll("[data-basket-step-link]"));
  var stepIcons = Array.from(basket.querySelectorAll("[data-basket-step-icon]"));
  var projectToggle = basket.querySelector("[data-basket-project-toggle]");
  var projectPanel = basket.querySelector("[data-basket-project-panel]");
  var requestPanel = basket.querySelector("[data-basket-request-panel]");
  var orderForm = basket.querySelector("[data-basket-order-form]");
  var formMessage = basket.querySelector("[data-basket-form-message]");
  var fileInput = basket.querySelector("[data-basket-file-input]");
  var filesSummary = basket.querySelector("[data-basket-files-summary]");
  var filesList = basket.querySelector("[data-basket-files-list]");
  var filesPlaceholder = basket.querySelector("[data-basket-files-placeholder]");
  var defaultFilesPlaceholderText = filesPlaceholder
    ? String(filesPlaceholder.textContent || "").trim()
    : "";
  var selectedFilesPlaceholderText = "Нажмите, чтобы добавить еще файлы";
  var stepOrder = ["cart", "details", "request"];
  var cartButtonText = nextButton ? nextButton.getAttribute("data-basket-text-cart") || nextButton.textContent : "";
  var detailsButtonText = nextButton ? nextButton.getAttribute("data-basket-text-details") || cartButtonText : "";
  var currentStep = "cart";
  var isSubmitting = false;
  var selectedFiles = [];

  function getStepIndex(step) {
    return stepOrder.indexOf(step);
  }

  function canNavigateToStep(step) {
    var stepIndex = getStepIndex(step);
    var currentIndex = getStepIndex(currentStep);
    return stepIndex > -1 && currentIndex > -1 && stepIndex < currentIndex;
  }

  function syncStepUi(step) {
    currentStep = step;
    var currentIndex = getStepIndex(step);

    basket.classList.toggle("is-details-step", step === "details");
    basket.classList.toggle("is-request-step", step === "request");

    if (requestPanel) {
      requestPanel.hidden = step !== "request";
    }

    stepLinks.forEach(function (link) {
      var linkStep = link.getAttribute("data-basket-step-link");
      var linkIndex = getStepIndex(linkStep);
      var isActive = linkStep === step;
      var isClickable = linkIndex > -1 && currentIndex > -1 && linkIndex < currentIndex;

      link.classList.toggle("active", isActive);
      link.classList.toggle("is-clickable", isClickable);
      link.setAttribute("tabindex", isClickable ? "0" : "-1");
      link.setAttribute("role", "button");
      link.setAttribute("aria-disabled", isClickable ? "false" : "true");
    });

    stepIcons.forEach(function (icon) {
      var isActive = icon.getAttribute("data-basket-step-icon") === step;
      icon.classList.toggle("active", isActive);
    });

    if (nextButton) {
      nextButton.textContent = step === "details" ? detailsButtonText : cartButtonText;
    }
  }

  function setProjectPanelState(isOpen) {
    if (!projectToggle || !projectPanel) {
      return;
    }

    projectToggle.classList.toggle("is-open", isOpen);
    projectToggle.setAttribute("aria-expanded", isOpen ? "true" : "false");
    projectPanel.hidden = !isOpen;
  }

  function setFormMessage(message, isError) {
    if (!formMessage) {
      return;
    }

    var hasMessage = typeof message === "string" && message.trim().length > 0;
    formMessage.hidden = !hasMessage;
    formMessage.textContent = hasMessage ? message.trim() : "";
    formMessage.style.color = isError ? "#BC5555" : "#2f855a";
  }

  function normalizePhoneDigits(value) {
    var digits = (value || "").replace(/\D/g, "");

    if (!digits.length) {
      return "";
    }

    if (digits.charAt(0) === "8") {
      digits = "7" + digits.slice(1);
    } else if (digits.charAt(0) === "9") {
      digits = "7" + digits;
    } else if (digits.charAt(0) !== "7") {
      digits = "7" + digits;
    }

    return digits.slice(0, 11);
  }

  function formatRuPhone(value) {
    var digits = normalizePhoneDigits(value);

    if (!digits.length) {
      return "";
    }

    var localDigits = digits.slice(1);
    var formatted = "+7";

    if (localDigits.length > 0) {
      formatted += " (" + localDigits.slice(0, 3);
    }

    if (localDigits.length >= 3) {
      formatted += ")";
    }

    if (localDigits.length > 3) {
      formatted += " " + localDigits.slice(3, 6);
    }

    if (localDigits.length > 6) {
      formatted += "-" + localDigits.slice(6, 8);
    }

    if (localDigits.length > 8) {
      formatted += "-" + localDigits.slice(8, 10);
    }

    return formatted;
  }

  var fieldMessages = {
    name: {
      required: "Ваше имя",
    },
    email: {
      required: "Ваш email",
      invalid: "Введите корректный email",
    },
    phone: {
      required: "Ваш номер телефона",
      invalid: "Введите корректный номер телефона",
    },
  };

  function getFieldNode(fieldName) {
    if (!orderForm) {
      return null;
    }

    return orderForm.querySelector('[data-form-field="' + fieldName + '"]');
  }

  function getErrorNode(fieldName) {
    if (!orderForm) {
      return null;
    }

    return orderForm.querySelector('[data-form-error="' + fieldName + '"]');
  }

  function showError(fieldName, message) {
    var field = getFieldNode(fieldName);
    var error = getErrorNode(fieldName);

    if (field) {
      field.classList.add("is-invalid");
      field.setAttribute("aria-invalid", "true");
    }

    if (error) {
      error.textContent = message;
      error.classList.add("is-visible");
    }
  }

  function clearError(fieldName) {
    var field = getFieldNode(fieldName);
    var error = getErrorNode(fieldName);

    if (field) {
      field.classList.remove("is-invalid");
      field.setAttribute("aria-invalid", "false");
    }

    if (error) {
      error.textContent = "";
      error.classList.remove("is-visible");
    }
  }

  function isValidEmail(value) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value);
  }

  function validateField(fieldName) {
    var field = getFieldNode(fieldName);
    if (!field) {
      return true;
    }

    var value = field.value.trim();
    var messages = fieldMessages[fieldName] || {};

    if (!value.length) {
      showError(fieldName, messages.required || "Поле обязательно");
      return false;
    }

    if (fieldName === "phone") {
      var digits = normalizePhoneDigits(value);
      if (digits.length < 11) {
        showError(fieldName, messages.invalid || "Некорректное значение");
        return false;
      }
    }

    if (fieldName === "email" && !isValidEmail(value)) {
      showError(fieldName, messages.invalid || "Некорректное значение");
      return false;
    }

    clearError(fieldName);
    return true;
  }

  function getFileFingerprint(file) {
    return [
      file && file.name ? String(file.name) : "",
      file && Number.isFinite(file.size) ? String(file.size) : "",
      file && Number.isFinite(file.lastModified) ? String(file.lastModified) : "",
      file && file.type ? String(file.type) : "",
    ].join("::");
  }

  function getSelectedFiles() {
    if (selectedFiles.length > 0) {
      return selectedFiles.slice();
    }

    if (!fileInput) {
      return [];
    }

    return Array.from(fileInput.files || []);
  }

  function syncInputFilesFromSelectedFiles() {
    if (!fileInput) {
      return;
    }

    if (typeof DataTransfer === "undefined") {
      return;
    }

    var dataTransfer = new DataTransfer();
    selectedFiles.forEach(function (file) {
      dataTransfer.items.add(file);
    });
    fileInput.files = dataTransfer.files;
  }

  function mergeSelectedFiles(filesToAdd) {
    if (!Array.isArray(filesToAdd) || filesToAdd.length === 0) {
      return;
    }

    var current = getSelectedFiles();
    var known = {};

    current.forEach(function (file) {
      known[getFileFingerprint(file)] = true;
    });

    filesToAdd.forEach(function (file) {
      var key = getFileFingerprint(file);
      if (!known[key]) {
        known[key] = true;
        current.push(file);
      }
    });

    selectedFiles = current;
    syncInputFilesFromSelectedFiles();
  }

  function clearSelectedFiles() {
    selectedFiles = [];

    if (!fileInput) {
      return;
    }

    if (typeof DataTransfer !== "undefined") {
      var dataTransfer = new DataTransfer();
      fileInput.files = dataTransfer.files;
      return;
    }

    fileInput.value = "";
  }

  function updateSelectedFilesUi() {
    if (!fileInput || !filesSummary || !filesList) {
      return;
    }

    var files = getSelectedFiles();
    var hasFiles = files.length > 0;

    filesSummary.hidden = !hasFiles;
    filesList.hidden = !hasFiles;

    if (filesPlaceholder) {
      filesPlaceholder.hidden = false;
      filesPlaceholder.textContent = hasFiles
        ? selectedFilesPlaceholderText
        : defaultFilesPlaceholderText;
    }

    if (!hasFiles) {
      filesSummary.textContent = "";
      filesList.innerHTML = "";
      return;
    }

    filesSummary.textContent = "Выбрано файлов: " + String(files.length);
    filesList.innerHTML = "";
    files.forEach(function (file) {
      var sizeKb = Math.max(1, Math.round((file.size || 0) / 1024));
      var item = document.createElement("li");
      item.textContent = file.name + " (" + String(sizeKb) + " KB)";
      filesList.appendChild(item);
    });
  }

  function extractFirstError(errors) {
    if (!errors || typeof errors !== "object") {
      return "";
    }

    var keys = Object.keys(errors);
    for (var i = 0; i < keys.length; i += 1) {
      var messages = errors[keys[i]];
      if (Array.isArray(messages) && messages.length > 0) {
        return String(messages[0]);
      }
    }

    return "";
  }

  function getBasketItems() {
    if (!window.AutomationCart || typeof window.AutomationCart.get !== "function") {
      return [];
    }

    var cart = window.AutomationCart.get();
    if (!Array.isArray(cart)) {
      return [];
    }

    return cart.map(function (item) {
      var quantity = parseInt(item && item.qty, 10);
      if (!Number.isFinite(quantity) || quantity < 1) {
        quantity = 1;
      }

      var productId = parseInt(item && item.id, 10);
      var slug = item && item.slug ? String(item.slug).trim() : "";
      var title = item && item.title ? String(item.title).trim() : "";
      var image = item && item.image ? String(item.image).trim() : "";

      var normalized = {
        quantity: quantity,
        options: [],
      };

      if (Number.isFinite(productId) && productId > 0) {
        normalized.id = productId;
        normalized.product_id = productId;
      }

      if (slug.length > 0) {
        normalized.slug = slug;
        normalized.url = "/products/" + encodeURIComponent(slug);
      }

      if (title.length > 0) {
        normalized.name = title;
        normalized.title = title;
      }

      if (image.length > 0) {
        normalized.photo = image;
        normalized.image = image;
      }

      return normalized;
    }).filter(function (item) {
      return !!item.id || !!item.product_id || !!item.slug;
    });
  }

  async function submitOrderForm() {
    if (!orderForm || isSubmitting) {
      return false;
    }

    var isNameValid = validateField("name");
    var isEmailValid = validateField("email");
    var isPhoneValid = validateField("phone");

    if (!isNameValid || !isEmailValid || !isPhoneValid) {
      return false;
    }

    var agreement = orderForm.querySelector("[data-basket-agreement]");
    if (agreement && !agreement.checked) {
      setFormMessage("Подтвердите обработку персональных данных.", true);
      agreement.focus();
      return false;
    }

    var items = getBasketItems();
    if (items.length === 0) {
      setFormMessage("Корзина пуста. Добавьте товары перед отправкой заявки.", true);
      return false;
    }

    var action = orderForm.getAttribute("action") || "/order-request";
    var method = (orderForm.getAttribute("method") || "POST").toUpperCase();
    var formData = new FormData(orderForm);
    formData.set("items", JSON.stringify(items));

    var files = getSelectedFiles();
    formData.delete("attachment[]");
    formData.delete("attachment");
    formData.delete("files[]");
    formData.delete("files");
    files.forEach(function (file) {
      formData.append("attachment[]", file, file.name);
    });

    setFormMessage("Отправляем заявку...", false);
    isSubmitting = true;
    if (nextButton) {
      nextButton.disabled = true;
    }

    try {
      var response = await fetch(action, {
        method: method,
        body: formData,
        headers: {
          Accept: "application/json",
          "X-Requested-With": "XMLHttpRequest",
        },
      });

      var payload = null;
      try {
        payload = await response.json();
      } catch (_jsonError) {
        payload = null;
      }

      if (!response.ok) {
        var firstError = extractFirstError(payload && payload.errors);
        var errorMessage = firstError || (payload && payload.message) || "Не удалось отправить заявку.";
        setFormMessage(errorMessage, true);
        return false;
      }

      setFormMessage("", false);

      if (orderForm) {
        orderForm.reset();
      }
      ["name", "email", "phone"].forEach(clearError);
      clearSelectedFiles();
      updateSelectedFilesUi();

      if (window.AutomationCart && typeof window.AutomationCart.clear === "function") {
        window.AutomationCart.clear();
      }

      return true;
    } catch (_error) {
      setFormMessage("Ошибка отправки. Проверьте интернет и повторите попытку.", true);
      return false;
    } finally {
      isSubmitting = false;
      if (nextButton) {
        nextButton.disabled = false;
      }
    }
  }

  if (nextButton) {
    nextButton.addEventListener("click", function () {
      if (currentStep === "cart") {
        syncStepUi("details");
        return;
      }

      if (currentStep === "details") {
        submitOrderForm().then(function (isSuccess) {
          if (isSuccess) {
            syncStepUi("request");
          }
        });
      }
    });
  }

  if (stepLinks.length) {
    stepLinks.forEach(function (link) {
      link.addEventListener("click", function () {
        var targetStep = link.getAttribute("data-basket-step-link");
        if (canNavigateToStep(targetStep)) {
          syncStepUi(targetStep);
        }
      });

      link.addEventListener("keydown", function (event) {
        if (event.key !== "Enter" && event.key !== " ") {
          return;
        }

        var targetStep = link.getAttribute("data-basket-step-link");
        if (!canNavigateToStep(targetStep)) {
          return;
        }

        event.preventDefault();
        syncStepUi(targetStep);
      });
    });
  }

  if (projectToggle && projectPanel) {
    projectToggle.addEventListener("click", function () {
      setProjectPanelState(projectPanel.hidden);
    });
    setProjectPanelState(false);
  }

  if (fileInput) {
    fileInput.addEventListener("change", function () {
      mergeSelectedFiles(Array.from(fileInput.files || []));
      updateSelectedFilesUi();
    });
  }

  ["name", "email", "phone"].forEach(function (fieldName) {
    var field = getFieldNode(fieldName);
    if (!field) {
      return;
    }

    field.addEventListener("input", function () {
      if (fieldName === "phone") {
        field.value = formatRuPhone(field.value);
      }

      if (field.classList.contains("is-invalid")) {
        validateField(fieldName);
      }
    });

    if (fieldName === "phone") {
      field.addEventListener("focus", function () {
        if (!field.value.trim()) {
          field.value = "+7 (";
        }
      });

      field.addEventListener("blur", function () {
        if (field.value === "+7 (" || field.value === "+7") {
          field.value = "";
        }
      });
    }

    field.addEventListener("blur", function () {
      validateField(fieldName);
    });
  });

  var basketPhoneField = getFieldNode("phone");
  if (basketPhoneField) {
    basketPhoneField.value = formatRuPhone(basketPhoneField.value);
  }

  updateSelectedFilesUi();

  syncStepUi("cart");
})();

(function () {
  var STORAGE_KEY = "automation_cart_v1";
  var COOKIE_KEY = "automation_cart_v1";
  var STORAGE_EVENT = "automation-cart:changed";
  var FALLBACK_IMAGE = "/assets/272582fd9c288dc352c6e9e18a1c2ebd46283542.png";

  function toInt(value, fallbackValue) {
    var parsed = parseInt(value, 10);
    if (!Number.isFinite(parsed)) {
      return fallbackValue;
    }
    return parsed;
  }

  function toString(value) {
    if (value === null || value === undefined) {
      return "";
    }
    return String(value).trim();
  }

  function safeParseJson(raw) {
    if (!raw) {
      return null;
    }

    try {
      return JSON.parse(raw);
    } catch (_error) {
      return null;
    }
  }

  function escapeHtml(value) {
    return String(value)
      .replace(/&/g, "&amp;")
      .replace(/</g, "&lt;")
      .replace(/>/g, "&gt;")
      .replace(/"/g, "&quot;")
      .replace(/'/g, "&#39;");
  }

  function getItemKey(item) {
    var id = toInt(item.id, 0);
    if (id > 0) {
      return "id:" + id;
    }

    var slug = toString(item.slug);
    if (slug.length) {
      return "slug:" + slug;
    }

    return "title:" + toString(item.title).toLowerCase();
  }

  function normalizeItem(item) {
    var normalized = {
      id: toInt(item && item.id, 0),
      slug: toString(item && item.slug),
      title: toString(item && item.title) || "\u0422\u043e\u0432\u0430\u0440",
      image: toString(item && item.image) || FALLBACK_IMAGE,
      brand: toString(item && item.brand),
      category: toString(item && item.category),
      qty: Math.max(1, toInt(item && item.qty, 1))
    };

    normalized.key = getItemKey(normalized);
    return normalized;
  }

  function normalizeCart(items) {
    if (!Array.isArray(items)) {
      return [];
    }

    var map = {};
    items.forEach(function (item) {
      var normalized = normalizeItem(item);
      if (!normalized.key) {
        return;
      }

      if (!map[normalized.key]) {
        map[normalized.key] = normalized;
        return;
      }

      map[normalized.key].qty += normalized.qty;
    });

    return Object.keys(map).map(function (key) {
      return map[key];
    });
  }

  function readCookie(name) {
    var cookieString = document.cookie || "";
    var pattern = new RegExp("(?:^|; )" + name.replace(/[.*+?^${}()|[\]\\]/g, "\\$&") + "=([^;]*)");
    var match = cookieString.match(pattern);
    return match ? decodeURIComponent(match[1]) : "";
  }

  function writeCookie(name, value) {
    try {
      var maxAge = 60 * 60 * 24 * 365;
      document.cookie = name + "=" + encodeURIComponent(value) + "; path=/; max-age=" + maxAge + "; SameSite=Lax";
    } catch (_error) {
      // Ignore cookie write failures (size limits / browser policy).
    }
  }

  function readCart() {
    var fromStorage = safeParseJson(window.localStorage.getItem(STORAGE_KEY));
    if (Array.isArray(fromStorage)) {
      return normalizeCart(fromStorage);
    }

    var fromCookie = safeParseJson(readCookie(COOKIE_KEY));
    if (Array.isArray(fromCookie)) {
      var normalized = normalizeCart(fromCookie);
      window.localStorage.setItem(STORAGE_KEY, JSON.stringify(normalized));
      return normalized;
    }

    return [];
  }

  function emitCartChanged(cart) {
    window.dispatchEvent(new CustomEvent(STORAGE_EVENT, { detail: { cart: cart } }));
  }

  function saveCart(cart) {
    var normalized = normalizeCart(cart);
    window.localStorage.setItem(STORAGE_KEY, JSON.stringify(normalized));
    writeCookie(COOKIE_KEY, JSON.stringify(normalized));
    emitCartChanged(normalized);
    return normalized;
  }

  function getCart() {
    return readCart();
  }

  function addToCart(item, qty) {
    var normalized = normalizeItem(item || {});
    normalized.qty = Math.max(1, toInt(qty, normalized.qty));

    var cart = readCart();
    var existing = cart.find(function (entry) {
      return entry.key === normalized.key;
    });

    if (existing) {
      existing.qty += normalized.qty;
    } else {
      cart.push(normalized);
    }

    return saveCart(cart);
  }

  function setQty(itemKey, qty) {
    var nextQty = Math.max(1, toInt(qty, 1));
    var cart = readCart();

    cart = cart.map(function (entry) {
      if (entry.key !== itemKey) {
        return entry;
      }
      entry.qty = nextQty;
      return entry;
    });

    return saveCart(cart);
  }

  function removeFromCart(itemKey) {
    var cart = readCart().filter(function (entry) {
      return entry.key !== itemKey;
    });
    return saveCart(cart);
  }

  function clearCart() {
    return saveCart([]);
  }

  function getTotalCount(cart) {
    return (cart || []).reduce(function (sum, entry) {
      return sum + Math.max(1, toInt(entry.qty, 1));
    }, 0);
  }

  function updateHeaderBasketLabel(cart) {
    var badges = document.querySelectorAll(".header__basket-count");
    var baskets = document.querySelectorAll(".header__basket");
    if (!badges.length && !baskets.length) {
      return;
    }

    var totalCount = getTotalCount(cart);
    var basketLabel = "\u041a\u043e\u0440\u0437\u0438\u043d\u0430";

    badges.forEach(function (badge) {
      if (totalCount > 0) {
        badge.textContent = String(totalCount);
        badge.hidden = false;
      } else {
        badge.textContent = "";
        badge.hidden = true;
      }
    });

    baskets.forEach(function (basket) {
      var ariaLabel = totalCount > 0
        ? basketLabel + ": " + totalCount
        : basketLabel;
      basket.setAttribute("aria-label", ariaLabel);
    });
  }

  function renderBasketProducts(cart) {
    var basketRoot = document.querySelector(".basket");
    if (!basketRoot) {
      return;
    }

    var cartPanel = basketRoot.querySelector(".basket__products.basket__panel--cart");
    if (!cartPanel) {
      return;
    }

    if (!Array.isArray(cart) || cart.length === 0) {
      cartPanel.innerHTML = [
        '<div class="main-empty-state">',
        '<p class="main-empty-state__title">\u041a\u043e\u0440\u0437\u0438\u043d\u0430 \u043f\u0443\u0441\u0442\u0430</p>',
        '<p class="main-empty-state__text">\u0414\u043e\u0431\u0430\u0432\u044c\u0442\u0435 \u0442\u043e\u0432\u0430\u0440\u044b \u0438\u0437 \u043a\u0430\u0442\u0430\u043b\u043e\u0433\u0430.</p>',
        "</div>"
      ].join("");
      return;
    }

    cartPanel.innerHTML = cart.map(function (item) {
      var image = escapeHtml(item.image || FALLBACK_IMAGE);
      var title = escapeHtml(item.title || "\u0422\u043e\u0432\u0430\u0440");
      var brand = escapeHtml(item.brand || "-");
      var category = escapeHtml(item.category || "-");
      var qty = Math.max(1, toInt(item.qty, 1));
      var key = escapeHtml(item.key);
      var minusDisabledClass = qty <= 1 ? " is-disabled" : "";
      var minusAriaDisabled = qty <= 1 ? "true" : "false";

      return [
        '<div class="basket__product" data-cart-key="', key, '">',
          '<div class="basket__img">',
            '<img src="', image, '" alt="', title, '">',
          "</div>",
          '<div class="basket__content">',
            '<div class="basket__info">',
              '<div class="basket__params">',
                '<p class="basket__name">', title, "</p>",
                '<div class="card__params" style="padding: unset;">',
                  '<div class="card__param">',
                    '<span class="card__param-name">\u0411\u0440\u0435\u043d\u0434</span>',
                    '<span class="card__param-dots" aria-hidden="true"></span>',
                    '<span class="card__param-value">', brand, "</span>",
                  "</div>",
                  '<div class="card__param">',
                    '<span class="card__param-name">\u041a\u0430\u0442\u0435\u0433\u043e\u0440\u0438\u044f</span>',
                    '<span class="card__param-dots" aria-hidden="true"></span>',
                    '<span class="card__param-value">', category, "</span>",
                  "</div>",
                "</div>",
              "</div>",
              '<div class="basket__numbers">',
                '<div class="card__price-number">',
                  '<button class="card__price-item btn card__qty-btn card__qty-btn--minus', minusDisabledClass, '" type="button" data-cart-action="decrease" aria-disabled="', minusAriaDisabled, '">',
                    '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">',
                      '<path d="M15.8334 9.16699H4.16671C3.94569 9.16699 3.73373 9.25479 3.57745 9.41107C3.42117 9.56735 3.33337 9.77931 3.33337 10.0003C3.33337 10.2213 3.42117 10.4333 3.57745 10.5896C3.73373 10.7459 3.94569 10.8337 4.16671 10.8337H15.8334C16.0544 10.8337 16.2663 10.7459 16.4226 10.5896C16.5789 10.4333 16.6667 10.2213 16.6667 10.0003C16.6667 9.77931 16.5789 9.56735 16.4226 9.41107C16.2663 9.25479 16.0544 9.16699 15.8334 9.16699Z" fill="#D6D6D9"/>',
                    "</svg>",
                  "</button>",
                  '<div class="card__price-item" data-cart-qty>', String(qty), "</div>",
                  '<button class="card__price-item btn card__qty-btn card__qty-btn--plus" type="button" data-cart-action="increase">',
                    '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">',
                      '<path d="M15.8334 9.16634H10.8334V4.16634C10.8334 3.94533 10.7456 3.73337 10.5893 3.57709C10.433 3.42081 10.2211 3.33301 10 3.33301C9.77903 3.33301 9.56707 3.42081 9.41079 3.57709C9.2545 3.73337 9.16671 3.94533 9.16671 4.16634V9.16634H4.16671C3.94569 9.16634 3.73373 9.25414 3.57745 9.41042C3.42117 9.5667 3.33337 9.77866 3.33337 9.99967C3.33337 10.2207 3.42117 10.4326 3.57745 10.5889C3.73373 10.7452 3.94569 10.833 4.16671 10.833H9.16671V15.833C9.16671 16.054 9.2545 16.266 9.41079 16.4223C9.56707 16.5785 9.77903 16.6663 10 16.6663C10.2211 16.6663 10.433 16.5785 10.5893 16.4223C10.7456 16.266 10.8334 16.054 10.8334 15.833V10.833H15.8334C16.0544 10.833 16.2663 10.7452 16.4226 10.5889C16.5789 10.4326 16.6667 10.2207 16.6667 9.99967C16.6667 9.77866 16.5789 9.5667 16.4226 9.41042C16.2663 9.25414 16.0544 9.16634 15.8334 9.16634Z" fill="#3873A9"/>',
                    "</svg>",
                  "</button>",
                "</div>",
                '<a href="#" class="basket__delete" data-cart-action="remove">\u0423\u0434\u0430\u043b\u0438\u0442\u044c</a>',
              "</div>",
            "</div>",
            '<p class="basket__price">\u041f\u043e \u0437\u0430\u043f\u0440\u043e\u0441\u0443</p>',
          "</div>",
        "</div>"
      ].join("");
    }).join("");
  }

  function bindBasketActions() {
    var basketRoot = document.querySelector(".basket");
    if (!basketRoot) {
      return;
    }

    var cartPanel = basketRoot.querySelector(".basket__products.basket__panel--cart");
    if (!cartPanel) {
      return;
    }

    cartPanel.addEventListener("click", function (event) {
      var actionNode = event.target.closest("[data-cart-action]");
      if (!actionNode) {
        return;
      }

      var itemNode = actionNode.closest("[data-cart-key]");
      if (!itemNode) {
        return;
      }

      event.preventDefault();
      var itemKey = itemNode.getAttribute("data-cart-key");
      if (!itemKey) {
        return;
      }

      var action = actionNode.getAttribute("data-cart-action");
      var cart = readCart();
      var current = cart.find(function (entry) {
        return entry.key === itemKey;
      });

      if (!current) {
        return;
      }

      if (action === "increase") {
        setQty(itemKey, current.qty + 1);
        return;
      }

      if (action === "decrease") {
        setQty(itemKey, Math.max(1, current.qty - 1));
        return;
      }

      if (action === "remove") {
        removeFromCart(itemKey);
      }
    });
  }

  function bindProductAddToCart() {
    var productCard = document.querySelector(".card[data-page='product-card']");
    if (!productCard) {
      return;
    }

    var addButton = productCard.querySelector("[data-add-to-cart]");
    if (!addButton) {
      return;
    }

    var qtyRoot = productCard.querySelector("[data-qty]");
    var qtyNode = productCard.querySelector("[data-qty-value]");
    var minusButton = qtyRoot ? qtyRoot.querySelector("[data-qty-action='decrease']") : null;
    var labelAdd = toString(addButton.getAttribute("data-cart-label-add")) || "\u0412 \u0441\u0447\u0435\u0442";
    var labelInCart = toString(addButton.getAttribute("data-cart-label-in")) || "\u0412 \u043a\u043e\u0440\u0437\u0438\u043d\u0435";
    var productItem = {
      id: productCard.getAttribute("data-cart-product-id"),
      slug: productCard.getAttribute("data-cart-product-slug"),
      title: productCard.getAttribute("data-cart-product-title"),
      image: productCard.getAttribute("data-cart-product-image"),
      brand: productCard.getAttribute("data-cart-product-brand"),
      category: productCard.getAttribute("data-cart-product-category"),
    };
    var productKey = normalizeItem(productItem).key;

    function getSelectedQty() {
      var rawValue = qtyNode ? qtyNode.textContent : "1";
      return Math.max(0, toInt(rawValue, 1));
    }

    function setSelectedQty(nextQty) {
      if (!qtyNode) {
        return;
      }
      qtyNode.textContent = String(Math.max(0, toInt(nextQty, 0)));
    }

    function syncMinusState() {
      if (!minusButton) {
        return;
      }

      var min = 0;
      if (qtyRoot) {
        min = Math.max(0, toInt(qtyRoot.getAttribute("data-qty-min"), 0));
      }

      var isDisabled = getSelectedQty() <= min;
      minusButton.classList.toggle("is-disabled", isDisabled);
      minusButton.setAttribute("aria-disabled", isDisabled ? "true" : "false");
    }

    function findProductEntry(cart) {
      var source = Array.isArray(cart) ? cart : readCart();
      return source.find(function (entry) {
        return entry.key === productKey;
      }) || null;
    }

    function syncProductUi(cart) {
      var entry = findProductEntry(cart);
      if (entry) {
        setSelectedQty(entry.qty);
        addButton.textContent = labelInCart;
      } else {
        addButton.textContent = labelAdd;
      }
      syncMinusState();
    }

    addButton.addEventListener("click", function () {
      var existingEntry = findProductEntry();
      if (existingEntry) {
        window.location.href = "/basket";
        return;
      }

      var qty = getSelectedQty();
      if (qty <= 0) {
        removeFromCart(productKey);
        addButton.textContent = labelAdd;
        syncMinusState();
        return;
      }

      addToCart(productItem, qty);

      addButton.textContent = labelInCart;
    });

    if (qtyRoot) {
      qtyRoot.addEventListener("click", function (event) {
        var actionNode = event.target.closest("[data-qty-action]");
        if (!actionNode) {
          return;
        }

        window.setTimeout(function () {
          var entry = findProductEntry();
          if (!entry) {
            syncMinusState();
            return;
          }

          var qty = getSelectedQty();
          if (qty <= 0) {
            removeFromCart(productKey);
            addButton.textContent = labelAdd;
            syncMinusState();
            return;
          }

          setQty(productKey, qty);
          addButton.textContent = labelInCart;
        }, 0);
      });
    }

    window.addEventListener(STORAGE_EVENT, function (event) {
      var cart = event && event.detail ? event.detail.cart : readCart();
      syncProductUi(cart);
    });

    syncProductUi(readCart());
  }

  function initialize() {
    var initialCart = readCart();
    updateHeaderBasketLabel(initialCart);
    renderBasketProducts(initialCart);
    bindBasketActions();
    bindProductAddToCart();

    window.addEventListener(STORAGE_EVENT, function (event) {
      var cart = event && event.detail ? event.detail.cart : readCart();
      updateHeaderBasketLabel(cart);
      renderBasketProducts(cart);
    });

    window.addEventListener("storage", function (event) {
      if (event.key !== STORAGE_KEY) {
        return;
      }
      var cart = readCart();
      updateHeaderBasketLabel(cart);
      renderBasketProducts(cart);
    });
  }

  window.AutomationCart = {
    get: getCart,
    add: addToCart,
    setQty: setQty,
    remove: removeFromCart,
    clear: clearCart,
    save: saveCart,
  };

  initialize();
})();

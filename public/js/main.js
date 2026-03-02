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

  // Add a couple of test cards when there are too few slides for desktop.
  if (!grid.querySelector("[data-test-copy='true']") && baseSlides.length < 6) {
    var copiesToAdd = Math.min(2, 6 - baseSlides.length);
    for (var i = 0; i < copiesToAdd; i += 1) {
      var source = baseSlides[i % baseSlides.length];
      if (!source) {
        break;
      }

      var clone = source.cloneNode(true);
      clone.classList.add("swiper-slide");
      clone.setAttribute("data-test-copy", "true");
      grid.appendChild(clone);
    }
  }

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

    function syncMinusState() {
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
        if (current <= min) {
          return;
        }
        renderQuantity(current - 1);
      });
    }

    if (plusButton) {
      plusButton.addEventListener("click", function () {
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

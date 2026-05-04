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
        setOpenState(false);
      });
    });

    drawerMedia.addEventListener("change", function () {
      setOpenState(false);
      syncHeaderOffsets();
    });

    window.addEventListener("resize", syncHeaderOffsets);
    window.addEventListener("load", syncHeaderOffsets);
    syncHeaderOffsets();
  })();
</script>

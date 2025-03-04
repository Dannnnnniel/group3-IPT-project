(function () {
  "use strict";

  /**
   * Easy selector helper function
   */
  const select = (el, all = false) => {
    el = el.trim();
    if (all) {
      return [...document.querySelectorAll(el)];
    } else {
      return document.querySelector(el);
    }
  };

  /**
   * Easy event listener function
   */
  const on = (type, el, listener, all = false) => {
    if (all) {
      select(el, all).forEach((e) => e.addEventListener(type, listener));
    } else {
      select(el, all).addEventListener(type, listener);
    }
  };

  /**
   * Easy on scroll event listener
   */
  const onscroll = (el, listener) => {
    el.addEventListener("scroll", listener);
  };

  /**
   * Form Validation
   */
  const setupFormValidation = () => {
    const form = select("form");
    if (!form) return;

    const lastNameInput = select("#last_name");
    const ageInput = select("#age");

    form.addEventListener("submit", (event) => {
      let isValid = true;

      // Validate Last Name
      if (!lastNameInput.value.trim()) {
        isValid = false;
        alert("Last Name is required.");
      }

      // Validate Age
      if (!ageInput.value || isNaN(ageInput.value) || ageInput.value < 0) {
        isValid = false;
        alert("Age must be a valid positive number.");
      }

      if (!isValid) {
        event.preventDefault(); // Prevent form submission if validation fails
      }
    });
  };

  /**
   * Sidebar toggle
   */
  const setupSidebarToggle = () => {
    const toggleSidebarBtn = select(".toggle-sidebar-btn");
    if (toggleSidebarBtn) {
      on("click", ".toggle-sidebar-btn", () => {
        select("body").classList.toggle("toggle-sidebar");
      });
    }
  };

  /**
   * Search bar toggle
   */
  const setupSearchBarToggle = () => {
    const searchBarToggle = select(".search-bar-toggle");
    if (searchBarToggle) {
      on("click", ".search-bar-toggle", () => {
        select(".search-bar").classList.toggle("search-bar-show");
      });
    }
  };

  /**
   * Navbar links active state on scroll
   */
  const setupNavbarLinksActive = () => {
    const navbarlinks = select("#navbar .scrollto", true);
    const navbarlinksActive = () => {
      const position = window.scrollY + 200;
      navbarlinks.forEach((navbarlink) => {
        if (!navbarlink.hash) return;
        const section = select(navbarlink.hash);
        if (!section) return;
        if (
          position >= section.offsetTop &&
          position <= section.offsetTop + section.offsetHeight
        ) {
          navbarlink.classList.add("active");
        } else {
          navbarlink.classList.remove("active");
        }
      });
    };
    window.addEventListener("load", navbarlinksActive);
    onscroll(document, navbarlinksActive);
  };

  /**
   * Toggle .header-scrolled class to #header when page is scrolled
   */
  const setupHeaderScrolled = () => {
    const selectHeader = select("#header");
    if (selectHeader) {
      const headerScrolled = () => {
        if (window.scrollY > 100) {
          selectHeader.classList.add("header-scrolled");
        } else {
          selectHeader.classList.remove("header-scrolled");
        }
      };
      window.addEventListener("load", headerScrolled);
      onscroll(document, headerScrolled);
    }
  };

  /**
   * Back to top button
   */
  const setupBackToTop = () => {
    const backtotop = select(".back-to-top");
    if (backtotop) {
      const toggleBacktotop = () => {
        if (window.scrollY > 100) {
          backtotop.classList.add("active");
        } else {
          backtotop.classList.remove("active");
        }
      };
      window.addEventListener("load", toggleBacktotop);
      onscroll(document, toggleBacktotop);
    }
  };

  /**
   * Initiate tooltips
   */
  const setupTooltips = () => {
    const tooltipTriggerList = [].slice.call(
      document.querySelectorAll('[data-bs-toggle="tooltip"]')
    );
    tooltipTriggerList.map((tooltipTriggerEl) => {
      return new bootstrap.Tooltip(tooltipTriggerEl);
    });
  };

  /**
   * Initiate TinyMCE Editor
   */
  const setupTinyMCE = () => {
    const useDarkMode = window.matchMedia("(prefers-color-scheme: dark)").matches;
    if (select("textarea.tinymce-editor")) {
      tinymce.init({
        selector: "textarea.tinymce-editor",
        plugins:
          "preview importcss searchreplace autolink autosave save directionality code visualblocks visualchars fullscreen image link media codesample table charmap pagebreak nonbreaking anchor insertdatetime advlist lists wordcount help charmap quickbars emoticons accordion",
        editimage_cors_hosts: ["picsum.photos"],
        menubar: "file edit view insert format tools table help",
        toolbar:
          "undo redo | accordion accordionremove | blocks fontfamily fontsize | bold italic underline strikethrough | align numlist bullist | link image | table media | lineheight outdent indent| forecolor backcolor removeformat | charmap emoticons | code fullscreen preview | save print | pagebreak anchor codesample | ltr rtl",
        autosave_ask_before_unload: true,
        autosave_interval: "30s",
        autosave_prefix: "{path}{query}-{id}-",
        autosave_restore_when_empty: false,
        autosave_retention: "2m",
        image_advtab: true,
        height: 600,
        image_caption: true,
        quickbars_selection_toolbar:
          "bold italic | quicklink h2 h3 blockquote quickimage quicktable",
        noneditable_class: "mceNonEditable",
        toolbar_mode: "sliding",
        contextmenu: "link image table",
        skin: useDarkMode ? "oxide-dark" : "oxide",
        content_css: useDarkMode ? "dark" : "default",
        content_style:
          "body { font-family:Helvetica,Arial,sans-serif; font-size:16px }",
      });
    }
  };

  /**
   * Initiate Bootstrap validation check
   */
  const setupBootstrapValidation = () => {
    const needsValidation = document.querySelectorAll(".needs-validation");
    Array.prototype.slice.call(needsValidation).forEach((form) => {
      form.addEventListener(
        "submit",
        (event) => {
          if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
          }
          form.classList.add("was-validated");
        },
        false
      );
    });
  };

  /**
   * Initiate Datatables
   */
  const setupDatatables = () => {
    const datatables = select(".datatable", true);
    datatables.forEach((datatable) => {
      new simpleDatatables.DataTable(datatable, {
        perPageSelect: [5, 10, 15, ["All", -1]],
        columns: [
          { select: 2, sortSequence: ["desc", "asc"] },
          { select: 3, sortSequence: ["desc"] },
          { select: 4, cellClass: "green", headerClass: "red" },
        ],
      });
    });
  };

  /**
   * Autoresize echart charts
   */
  const setupEchartResize = () => {
    const mainContainer = select("#main");
    if (mainContainer) {
      setTimeout(() => {
        new ResizeObserver(() => {
          select(".echart", true).forEach((getEchart) => {
            echarts.getInstanceByDom(getEchart).resize();
          });
        }).observe(mainContainer);
      }, 200);
    }
  };

  /**
   * Initialize all functionalities
   */
  const init = () => {
    setupFormValidation();
    setupSidebarToggle();
    setupSearchBarToggle();
    setupNavbarLinksActive();
    setupHeaderScrolled();
    setupBackToTop();
    setupTooltips();
    setupTinyMCE();
    setupBootstrapValidation();
    setupDatatables();
    setupEchartResize();
  };

  // Initialize everything on DOM load
  document.addEventListener("DOMContentLoaded", init);
})();
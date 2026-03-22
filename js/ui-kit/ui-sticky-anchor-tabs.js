(function (window) {
  "use strict";

  window.CommonUI = window.CommonUI || {};

  window.CommonUI.initStickyAnchorTabs = function (options = {}) {
    if (typeof window.jQuery === "undefined") {
      return null;
    }

    const $ = window.jQuery;
    const settings = $.extend(
      {
        tabBarSelector: "",
        tabNavSelector: "",
        tabLinkSelector: "a[href^='#']",
        topbarSelector: "#hd_top",
        heightVarName: "",
        scrollGap: 8,
        scrollDuration: 180,
        namespace: "stickyAnchorTabs"
      },
      options || {}
    );

    const $tabBar = $(settings.tabBarSelector);
    const $tabNav = $(settings.tabNavSelector);

    if (!$tabBar.length || !$tabNav.length) {
      return null;
    }

    const $tabLinks = $tabNav.find(settings.tabLinkSelector);
    if (!$tabLinks.length) {
      return null;
    }

    const sections = [];
    $tabLinks.each(function () {
      const targetId = $(this).attr("href");
      const $target = $(targetId);

      if ($target.length) {
        sections.push({
          id: targetId,
          $section: $target
        });
      }
    });

    if (!sections.length) {
      return null;
    }

    const namespace = "." + settings.namespace;
    let currentTabId = "";
    let scrollTicking = false;
    let stickyStartTop = 0;
    let isProgrammaticScroll = false;
    let isScrollSpySuspended = false;
    let pendingTabId = "";
    let scrollReleaseTimer = 0;
    const hashlessUrl = window.location.pathname + window.location.search;

    function getScrollOffset() {
      const topbarHeight = $(settings.topbarSelector).outerHeight() || 0;
      const tabbarHeight = $tabBar.outerHeight() || 0;
      return topbarHeight + tabbarHeight + settings.scrollGap;
    }

    function updateStickyStartTop() {
      const topbarHeight = $(settings.topbarSelector).outerHeight() || 0;
      stickyStartTop = Math.max(0, $tabBar.offset().top - topbarHeight);
    }

    function updateTabbarHeightVar() {
      if (!settings.heightVarName) {
        return;
      }

      const tabbarHeight = $tabBar.outerHeight() || 0;
      document.documentElement.style.setProperty(settings.heightVarName, tabbarHeight + "px");
    }

    function updateStickyVisualState() {
      $tabBar.toggleClass("is-stuck", $(window).scrollTop() > stickyStartTop);
    }

    function updateOverflowIndicators() {
      const navEl = $tabNav.get(0);
      if (!navEl) {
        return;
      }

      const maxScroll = navEl.scrollWidth - navEl.clientWidth;
      const hasOverflow = maxScroll > 0.5;
      const isLeftOverflow = navEl.scrollLeft > 0.5;
      const isRightOverflow = navEl.scrollLeft < maxScroll - 0.5;

      $tabBar.toggleClass("has-overflow", hasOverflow);
      $tabBar.toggleClass("is-overflow-left", hasOverflow && isLeftOverflow);
      $tabBar.toggleClass("is-overflow-right", hasOverflow && isRightOverflow);
    }

    function ensureTabVisible($tabLink, smooth) {
      if (
        !$tabLink ||
        !$tabLink.length ||
        !$tabLink[0] ||
        typeof $tabLink[0].scrollIntoView !== "function"
      ) {
        return;
      }

      $tabLink[0].scrollIntoView({
        behavior: smooth ? "smooth" : "auto",
        block: "nearest",
        inline: "center"
      });
    }

    function setActiveTab(tabId, smoothTab) {
      if (!tabId || currentTabId === tabId) {
        return;
      }

      currentTabId = tabId;
      $tabLinks
        .removeClass("active")
        .attr("aria-selected", "false")
        .attr("tabindex", "-1");

      const $activeTab = $tabLinks.filter("[href='" + tabId + "']");
      $activeTab
        .addClass("active")
        .attr("aria-selected", "true")
        .attr("tabindex", "0");

      ensureTabVisible($activeTab, !!smoothTab);
    }

    function clearScrollReleaseTimer() {
      if (!scrollReleaseTimer) {
        return;
      }

      window.clearTimeout(scrollReleaseTimer);
      scrollReleaseTimer = 0;
    }

    function releaseProgrammaticScroll(options = {}) {
      const shouldSyncWithScrollSpy = !!options.syncWithScrollSpy;
      const keepScrollSpySuspended = !!options.keepScrollSpySuspended;

      if (!isProgrammaticScroll && !pendingTabId) {
        return;
      }

      const fallbackTabId = pendingTabId;
      isProgrammaticScroll = false;
      isScrollSpySuspended = keepScrollSpySuspended;
      pendingTabId = "";
      clearScrollReleaseTimer();

      if (shouldSyncWithScrollSpy && !isScrollSpySuspended) {
        setActiveTab(findActiveTabByScroll());
      } else if (fallbackTabId) {
        setActiveTab(fallbackTabId);
      }

      updateStickyVisualState();
    }

    function startProgrammaticScroll(tabId, $targetSection) {
      if (!$targetSection || !$targetSection.length) {
        return;
      }

      const targetScrollTop = Math.max(0, $targetSection.offset().top - getScrollOffset());

      $("html, body").stop(true);

      isProgrammaticScroll = true;
      isScrollSpySuspended = true;
      pendingTabId = tabId;
      clearScrollReleaseTimer();
      setActiveTab(tabId, true);
      window.history.replaceState(window.history.state, "", hashlessUrl);

      let isSettled = false;
      const finalizeScroll = function (finalizeOptions) {
        if (isSettled) {
          return;
        }

        isSettled = true;
        releaseProgrammaticScroll(finalizeOptions);
      };

      scrollReleaseTimer = window.setTimeout(function () {
        finalizeScroll({
          syncWithScrollSpy: false,
          keepScrollSpySuspended: true
        });
      }, settings.scrollDuration + 120);

      $("html, body").animate(
        {
          scrollTop: targetScrollTop
        },
        settings.scrollDuration,
        function () {
          finalizeScroll({
            syncWithScrollSpy: false,
            keepScrollSpySuspended: true
          });
        }
      );
    }

    function findActiveTabByScroll() {
      const marker = $(window).scrollTop() + getScrollOffset();
      let activeTabId = sections[0].id;

      for (let idx = 0; idx < sections.length; idx++) {
        if (sections[idx].$section.offset().top <= marker) {
          activeTabId = sections[idx].id;
        } else {
          break;
        }
      }

      return activeTabId;
    }

    function handleScroll() {
      if (scrollTicking) {
        return;
      }
      scrollTicking = true;

      window.requestAnimationFrame(function () {
        if (!isProgrammaticScroll && !isScrollSpySuspended) {
          setActiveTab(findActiveTabByScroll());
        }
        updateStickyVisualState();
        scrollTicking = false;
      });
    }

    function resumeScrollSpy() {
      if (!isScrollSpySuspended) {
        return;
      }

      isScrollSpySuspended = false;
      setActiveTab(findActiveTabByScroll());
      updateStickyVisualState();
    }

    function interruptProgrammaticScroll() {
      if (isProgrammaticScroll) {
        $("html, body").stop(true);
        releaseProgrammaticScroll({
          syncWithScrollSpy: false,
          keepScrollSpySuspended: false
        });
      } else {
        resumeScrollSpy();
      }
    }

    $tabLinks.off(namespace);
    $tabNav.off(namespace);
    $tabBar.off(namespace);
    $(window).off(namespace);

    $tabLinks.on("click" + namespace, function (e) {
      const tabId = $(this).attr("href");
      const $target = $(tabId);
      if (!$target.length) {
        return;
      }

      e.preventDefault();
      startProgrammaticScroll(tabId, $target);
    });

    $tabLinks.on("keydown" + namespace, function (e) {
      const key = e.key;
      const currentIndex = $tabLinks.index(this);
      let targetIndex = -1;

      if (key === "ArrowRight") {
        targetIndex = (currentIndex + 1) % $tabLinks.length;
      } else if (key === "ArrowLeft") {
        targetIndex = (currentIndex - 1 + $tabLinks.length) % $tabLinks.length;
      } else if (key === "Home") {
        targetIndex = 0;
      } else if (key === "End") {
        targetIndex = $tabLinks.length - 1;
      } else if (key === " " || key === "Enter") {
        targetIndex = currentIndex;
      } else {
        return;
      }

      e.preventDefault();

      const $targetTab = $tabLinks.eq(targetIndex);
      const tabId = $targetTab.attr("href");
      const $targetSection = $(tabId);
      if (!$targetSection.length) {
        return;
      }

      $targetTab.trigger("focus");
      startProgrammaticScroll(tabId, $targetSection);
    });

    $tabNav.on("wheel" + namespace, function (e) {
      const evt = e.originalEvent;
      const navEl = this;

      if (!evt || !navEl) {
        return;
      }

      if (Math.abs(evt.deltaY) <= Math.abs(evt.deltaX)) {
        return;
      }

      if (navEl.scrollWidth <= navEl.clientWidth + 1) {
        return;
      }

      navEl.scrollLeft += evt.deltaY;
      e.preventDefault();
      updateOverflowIndicators();
    });

    $tabNav.on("scroll" + namespace, updateOverflowIndicators);
    $tabBar.on("mouseenter" + namespace + " focusin" + namespace, updateOverflowIndicators);
    $(window).on("wheel" + namespace + " touchstart" + namespace, interruptProgrammaticScroll);

    $tabLinks.each(function () {
      const $tab = $(this);
      const targetId = $tab.attr("href");
      const $target = $(targetId);

      if (!$target.length) {
        return;
      }

      const tabId = $tab.attr("id");
      if (tabId) {
        $target.attr("role", "tabpanel").attr("aria-labelledby", tabId);
      }
    });

    $(window).on("scroll" + namespace, handleScroll);
    $(window).on("resize" + namespace, function () {
      updateStickyStartTop();
      updateTabbarHeightVar();
      if (!isScrollSpySuspended) {
        setActiveTab(findActiveTabByScroll());
      }
      updateStickyVisualState();
      updateOverflowIndicators();
    });

    updateStickyStartTop();
    updateTabbarHeightVar();
    updateStickyVisualState();
    updateOverflowIndicators();
    window.requestAnimationFrame(updateOverflowIndicators);
    window.setTimeout(updateOverflowIndicators, 120);

    if (document.fonts && document.fonts.ready && typeof document.fonts.ready.then === "function") {
      document.fonts.ready.then(updateOverflowIndicators);
    }

    const initialHash = window.location.hash;
    if (initialHash && $tabLinks.filter("[href='" + initialHash + "']").length) {
      setActiveTab(initialHash);
      window.setTimeout(function () {
        const $initialTarget = $(initialHash);
        if ($initialTarget.length) {
          $("html, body").stop().scrollTop($initialTarget.offset().top - getScrollOffset());
        }
        updateOverflowIndicators();
      }, 0);
    } else {
      setActiveTab(findActiveTabByScroll());
    }

    return {
      refresh: function () {
        updateStickyStartTop();
        updateTabbarHeightVar();
        if (!isScrollSpySuspended) {
          setActiveTab(findActiveTabByScroll());
        }
        updateStickyVisualState();
        updateOverflowIndicators();
      },
      destroy: function () {
        clearScrollReleaseTimer();
        $tabLinks.off(namespace);
        $tabNav.off(namespace);
        $tabBar.off(namespace);
        $(window).off(namespace);
      }
    };
  };
})(window);

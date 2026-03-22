(function (window) {
  "use strict";

  window.CommonUI = window.CommonUI || {};

  window.CommonUI.initStickyAnchorTabs = function (options) {
    var settings = Object.assign(
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

    var tabBar = document.querySelector(settings.tabBarSelector);
    var tabNav = document.querySelector(settings.tabNavSelector);

    if (!tabBar || !tabNav) {
      return null;
    }

    if (tabBar.__stickyAnchorTabs && typeof tabBar.__stickyAnchorTabs.destroy === "function") {
      tabBar.__stickyAnchorTabs.destroy();
    }

    var tabLinks = Array.prototype.slice.call(tabNav.querySelectorAll(settings.tabLinkSelector)).filter(function (link) {
      var targetId = link.getAttribute("href");
      return targetId && targetId.charAt(0) === "#";
    });

    if (!tabLinks.length) {
      return null;
    }

    var sections = tabLinks
      .map(function (link) {
        var targetId = link.getAttribute("href");
        var target = document.querySelector(targetId);

        if (!target) {
          return null;
        }

        return {
          id: targetId,
          section: target
        };
      })
      .filter(Boolean);

    if (!sections.length) {
      return null;
    }

    var currentTabId = "";
    var scrollTicking = false;
    var stickyStartTop = 0;
    var isProgrammaticScroll = false;
    var isScrollSpySuspended = false;
    var pendingTabId = "";
    var scrollReleaseTimer = 0;
    var hashlessUrl = window.location.pathname + window.location.search;
    var resizeHandler = null;
    var scrollHandler = null;

    function getOuterHeight(element) {
      return element ? element.getBoundingClientRect().height : 0;
    }

    function getOffsetTop(element) {
      var rect = element.getBoundingClientRect();
      return rect.top + window.pageYOffset;
    }

    function getScrollOffset() {
      var topbar = document.querySelector(settings.topbarSelector);
      var topbarHeight = getOuterHeight(topbar);
      var tabbarHeight = getOuterHeight(tabBar);
      return topbarHeight + tabbarHeight + settings.scrollGap;
    }

    function updateStickyStartTop() {
      var topbar = document.querySelector(settings.topbarSelector);
      var topbarHeight = getOuterHeight(topbar);
      stickyStartTop = Math.max(0, getOffsetTop(tabBar) - topbarHeight);
    }

    function updateTabbarHeightVar() {
      if (!settings.heightVarName) {
        return;
      }

      document.documentElement.style.setProperty(settings.heightVarName, getOuterHeight(tabBar) + "px");
    }

    function updateStickyVisualState() {
      tabBar.classList.toggle("is-stuck", window.pageYOffset > stickyStartTop);
    }

    function updateOverflowIndicators() {
      var maxScroll = tabNav.scrollWidth - tabNav.clientWidth;
      var hasOverflow = maxScroll > 0.5;
      var isLeftOverflow = tabNav.scrollLeft > 0.5;
      var isRightOverflow = tabNav.scrollLeft < maxScroll - 0.5;

      tabBar.classList.toggle("has-overflow", hasOverflow);
      tabBar.classList.toggle("is-overflow-left", hasOverflow && isLeftOverflow);
      tabBar.classList.toggle("is-overflow-right", hasOverflow && isRightOverflow);
    }

    function ensureTabVisible(tabLink, smooth) {
      if (!tabLink || typeof tabLink.scrollIntoView !== "function") {
        return;
      }

      tabLink.scrollIntoView({
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

      tabLinks.forEach(function (link) {
        var isActive = link.getAttribute("href") === tabId;
        link.classList.toggle("active", isActive);
        link.setAttribute("aria-selected", isActive ? "true" : "false");
        link.setAttribute("tabindex", isActive ? "0" : "-1");

        if (isActive) {
          ensureTabVisible(link, !!smoothTab);
        }
      });
    }

    function clearScrollReleaseTimer() {
      if (!scrollReleaseTimer) {
        return;
      }

      window.clearTimeout(scrollReleaseTimer);
      scrollReleaseTimer = 0;
    }

    function findActiveTabByScroll() {
      var marker = window.pageYOffset + getScrollOffset();
      var activeTabId = sections[0].id;

      sections.forEach(function (entry) {
        if (getOffsetTop(entry.section) <= marker) {
          activeTabId = entry.id;
        }
      });

      return activeTabId;
    }

    function releaseProgrammaticScroll(options) {
      var releaseOptions = options || {};
      var shouldSyncWithScrollSpy = !!releaseOptions.syncWithScrollSpy;
      var keepScrollSpySuspended = !!releaseOptions.keepScrollSpySuspended;

      if (!isProgrammaticScroll && !pendingTabId) {
        return;
      }

      var fallbackTabId = pendingTabId;
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

    function startProgrammaticScroll(tabId, targetSection) {
      if (!targetSection) {
        return;
      }

      var targetScrollTop = Math.max(0, getOffsetTop(targetSection) - getScrollOffset());

      isProgrammaticScroll = true;
      isScrollSpySuspended = true;
      pendingTabId = tabId;
      clearScrollReleaseTimer();
      setActiveTab(tabId, true);
      window.history.replaceState(window.history.state, "", hashlessUrl);

      scrollReleaseTimer = window.setTimeout(function () {
        releaseProgrammaticScroll({
          syncWithScrollSpy: false,
          keepScrollSpySuspended: true
        });
      }, settings.scrollDuration + 160);

      window.scrollTo({
        top: targetScrollTop,
        behavior: "smooth"
      });
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
        window.scrollTo({
          top: window.pageYOffset,
          behavior: "auto"
        });
        releaseProgrammaticScroll({
          syncWithScrollSpy: false,
          keepScrollSpySuspended: false
        });
      } else {
        resumeScrollSpy();
      }
    }

    tabLinks.forEach(function (link) {
      var targetId = link.getAttribute("href");
      var target = document.querySelector(targetId);
      var tabId = link.getAttribute("id");

      if (target && tabId) {
        target.setAttribute("role", "tabpanel");
        target.setAttribute("aria-labelledby", tabId);
      }

      link.addEventListener("click", function (event) {
        if (!target) {
          return;
        }

        event.preventDefault();
        startProgrammaticScroll(targetId, target);
      });

      link.addEventListener("keydown", function (event) {
        var key = event.key;
        var currentIndex = tabLinks.indexOf(link);
        var targetIndex = -1;

        if (key === "ArrowRight") {
          targetIndex = (currentIndex + 1) % tabLinks.length;
        } else if (key === "ArrowLeft") {
          targetIndex = (currentIndex - 1 + tabLinks.length) % tabLinks.length;
        } else if (key === "Home") {
          targetIndex = 0;
        } else if (key === "End") {
          targetIndex = tabLinks.length - 1;
        } else if (key === " " || key === "Enter") {
          targetIndex = currentIndex;
        } else {
          return;
        }

        event.preventDefault();

        var targetTab = tabLinks[targetIndex];
        var tabTargetId = targetTab.getAttribute("href");
        var targetSection = document.querySelector(tabTargetId);
        if (!targetSection) {
          return;
        }

        targetTab.focus();
        startProgrammaticScroll(tabTargetId, targetSection);
      });
    });

    tabNav.addEventListener("wheel", function (event) {
      if (Math.abs(event.deltaY) <= Math.abs(event.deltaX)) {
        return;
      }

      if (tabNav.scrollWidth <= tabNav.clientWidth + 1) {
        return;
      }

      tabNav.scrollLeft += event.deltaY;
      event.preventDefault();
      updateOverflowIndicators();
    }, { passive: false });

    tabNav.addEventListener("scroll", updateOverflowIndicators);
    tabBar.addEventListener("mouseenter", updateOverflowIndicators);
    tabBar.addEventListener("focusin", updateOverflowIndicators);
    window.addEventListener("wheel", interruptProgrammaticScroll, { passive: true });
    window.addEventListener("touchstart", interruptProgrammaticScroll, { passive: true });

    scrollHandler = function () {
      handleScroll();
    };

    resizeHandler = function () {
      updateStickyStartTop();
      updateTabbarHeightVar();
      if (!isScrollSpySuspended) {
        setActiveTab(findActiveTabByScroll());
      }
      updateStickyVisualState();
      updateOverflowIndicators();
    };

    window.addEventListener("scroll", scrollHandler, { passive: true });
    window.addEventListener("resize", resizeHandler);

    updateStickyStartTop();
    updateTabbarHeightVar();
    updateStickyVisualState();
    updateOverflowIndicators();
    window.requestAnimationFrame(updateOverflowIndicators);
    window.setTimeout(updateOverflowIndicators, 120);

    if (document.fonts && document.fonts.ready && typeof document.fonts.ready.then === "function") {
      document.fonts.ready.then(updateOverflowIndicators);
    }

    var initialHash = window.location.hash;
    if (initialHash && tabLinks.some(function (link) { return link.getAttribute("href") === initialHash; })) {
      setActiveTab(initialHash);
      window.setTimeout(function () {
        var initialTarget = document.querySelector(initialHash);
        if (initialTarget) {
          window.scrollTo(0, Math.max(0, getOffsetTop(initialTarget) - getScrollOffset()));
        }
        updateOverflowIndicators();
      }, 0);
    } else {
      setActiveTab(findActiveTabByScroll());
    }

    var api = {
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
        window.removeEventListener("scroll", scrollHandler);
        window.removeEventListener("resize", resizeHandler);
        window.removeEventListener("wheel", interruptProgrammaticScroll, { passive: true });
        window.removeEventListener("touchstart", interruptProgrammaticScroll, { passive: true });
        if (tabBar.__stickyAnchorTabs === api) {
          delete tabBar.__stickyAnchorTabs;
        }
      }
    };

    tabBar.__stickyAnchorTabs = api;

    return api;
  };
})(window);

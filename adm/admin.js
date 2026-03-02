/** 공통 UI 모듈 */
window.CommonUI = {
  bindTabs(tabSelector, contentSelector, options = {}) {
    const tabs = document.querySelectorAll(tabSelector);
    const contents = document.querySelectorAll(contentSelector);

    tabs.forEach(tab => {
      tab.addEventListener('click', () => {
        const tabName = tab.dataset.tab;
        const target = document.getElementById(`tab-${tabName}`);

        tabs.forEach(t => t.classList.remove('active'));
        tab.classList.add('active');

        contents.forEach(c => c.classList.add('is-hidden'));

        if (target) target.classList.remove('is-hidden');

        options.onChange?.(tabName, target);
      });
    });
  },
  initStickyAnchorTabs(options = {}) {
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
      const isRightOverflow = navEl.scrollLeft < (maxScroll - 0.5);

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
        setActiveTab(findActiveTabByScroll());
        updateStickyVisualState();
        scrollTicking = false;
      });
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
      setActiveTab(tabId, true);
      window.history.replaceState(null, "", tabId);

      $("html, body")
        .stop()
        .animate(
          {
            scrollTop: $target.offset().top - getScrollOffset()
          },
          settings.scrollDuration
        );
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
      setActiveTab(tabId, true);
      window.history.replaceState(null, "", tabId);

      $("html, body")
        .stop()
        .animate(
          {
            scrollTop: $targetSection.offset().top - getScrollOffset()
          },
          settings.scrollDuration
        );
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

    // 탭과 섹션 접근성 연결
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
      setActiveTab(findActiveTabByScroll());
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
        setActiveTab(findActiveTabByScroll());
        updateStickyVisualState();
        updateOverflowIndicators();
      },
      destroy: function () {
        $tabLinks.off(namespace);
        $tabNav.off(namespace);
        $tabBar.off(namespace);
        $(window).off(namespace);
      }
    };
  }
};

function setHtml(el, markup) {
    if (!el) return; 
    if (markup == null || markup === '') { 
        el.textContent = '';
        return;
    }
    const range = document.createRange();
    range.selectNodeContents(el);
    el.replaceChildren(range.createContextualFragment(markup));
}

/** 팝업 관리 모듈 */
window.PopupManager = {
    open(id, options = {}) {
        const el = document.getElementById(id);
        if (el) {
            el.classList.remove('is-hidden');
            this.bindOutsideClickClose(id);

            if (!options.disableOutsideClose) {
                this.bindOutsideClickClose(id);
            } else {
                this.unbindOutsideClickClose(id);
            }
        }
    },

    close(id) {
        const el = document.getElementById(id);
        if (el) el.classList.add('is-hidden');
    },

    toggle(id) {
        const el = document.getElementById(id);
        if (el) el.classList.toggle('is-hidden');
    },

    bindOutsideClickClose(id) {
        const el = document.getElementById(id);
        if (!el) return;
        el.onclick = () => this.close(id);
    },

    unbindOutsideClickClose(id) {
        const el = document.getElementById(id);
        if (!el) return;
        el.onclick = null;
    },

    /**
     * 팝업 콘텐츠 렌더링 (타이틀, 바디, 푸터 구성)
     * @param {string} title - 팝업 제목
     * @param {string} body - 팝업 본문 HTML
     * @param {string} [footer] - 푸터 HTML
     * @param {object} [options] - 팝업 열기 옵션
     */
    render(title, body, footer = '', options = {}) {
        const titleEl = document.getElementById('popupTitle');
        const bodyEl = document.getElementById('popupBody');
        const footerEl = document.getElementById('popupFooter');

        if (titleEl) titleEl.textContent = title;
        if (bodyEl) setHtml(bodyEl, body);
        if (footerEl) setHtml(footerEl, footer);

        this.open('popupOverlay', options);
    }
};

/** 형식 체크 */
function check_all(target) {
    const chkboxes = document.getElementsByName("chk[]");
    let chkall;

    if (target && target.tagName === "FORM") {
        chkall = target.querySelector('input[name="chkall"]');
    } else if (target && target.type === "checkbox") {
        chkall = target;
    }

    if (!chkall) return;

    for (const checkbox of chkboxes) {
        checkbox.checked = chkall.checked;
    }
}


function btn_check(f, act)
{
    if (act == "update") // 선택수정
    {
        f.action = list_update_php;
        str = "수정";
    }
    else if (act == "delete") // 선택삭제
    {
        f.action = list_delete_php;
        str = "삭제";
    }
    else
        return;

    var chk = document.getElementsByName("chk[]");
    var bchk = false;

    for (i=0; i<chk.length; i++)
    {
        if (chk[i].checked)
            bchk = true;
    }

    if (!bchk)
    {
        alert(str + "할 자료를 하나 이상 선택하세요.");
        return;
    }

    if (act == "delete")
    {
        if (!confirm("선택한 자료를 정말 삭제 하시겠습니까?"))
            return;
    }

    f.submit();
}

function is_checked(elements_name)
{
    var checked = false;
    var chk = document.getElementsByName(elements_name);
    for (var i=0; i<chk.length; i++) {
        if (chk[i].checked) {
            checked = true;
        }
    }
    return checked;
}

function delete_confirm(el)
{
    if(confirm("한번 삭제한 자료는 복구할 방법이 없습니다.\n\n정말 삭제하시겠습니까?")) {
        var token = get_ajax_token();
        var href = el.href.replace(/&token=.+$/g, "");
        if(!token) {
            alert("토큰 정보가 올바르지 않습니다.");
            return false;
        }
        el.href = href+"&token="+token;
        return true;
    } else {
        return false;
    }
}

function delete_confirm2(msg)
{
    if(confirm(msg))
        return true;
    else
        return false;
}

function get_ajax_token()
{
    var token = "",
        admin_csrf_token_key = (typeof g5_admin_csrf_token_key !== "undefined") ? g5_admin_csrf_token_key : "";

    $.ajax({
        type: "POST",
        url: g5_admin_url+"/ajax.token.php",
        data : {admin_csrf_token_key:admin_csrf_token_key},
        cache: false,
        async: false,
        dataType: "json",
        success: function(data) {
            if(data.error) {
                alert(data.error);
                if(data.url)
                    document.location.href = data.url;

                return false;
            }

            token = data.token;
        }
    });

    return token;
}

$(function() {
    $(document).on("click", "form input:submit, form button:submit", function() {
        var f = this.form;
        var token = get_ajax_token();

        if(!token) {
            alert("토큰 정보가 올바르지 않습니다.");
            return false;
        }

        var $f = $(f);

        if(typeof f.token === "undefined")
            $f.prepend('<input type="hidden" name="token" value="">');

        $f.find("input[name=token]").val(token);

        return true;
    });
});

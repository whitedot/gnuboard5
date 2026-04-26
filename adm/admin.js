/** 공통 UI 모듈 */
window.CommonUI = window.CommonUI || {};

window.CommonUI.bindTabs = function (tabSelector, contentSelector, options = {}) {
  const tabs = document.querySelectorAll(tabSelector);
  const contents = document.querySelectorAll(contentSelector);

  tabs.forEach(tab => {
    tab.addEventListener('click', () => {
      const tabName = tab.dataset.tab;
      const target = document.getElementById(`tab-${tabName}`);

      tabs.forEach(t => t.classList.remove('active'));
      tab.classList.add('active');

      contents.forEach(c => c.classList.add('is-hidden'));

      if (target) {
        target.classList.remove('is-hidden');
      }

      options.onChange?.(tabName, target);
    });
  });
};

function setHtml(el, markup) {
    if (!el) {
        return;
    }

    if (markup == null || markup === '') {
        el.textContent = '';
        return;
    }

    const range = document.createRange();
    range.selectNodeContents(el);
    el.replaceChildren(range.createContextualFragment(markup));
}

window.tempX = window.tempX || 0;
window.tempY = window.tempY || 0;

window.imageview = function(id, w, h) {
    if (typeof window.menu === 'function') {
        window.menu(id);
    }

    const el = document.getElementById(id);
    if (!el) {
        return;
    }

    window.submenu = el.style;
    window.submenu.left = window.tempX - (w + 11);
    window.submenu.top = window.tempY - (h / 2);

    if (typeof window.selectBoxVisible === 'function') {
        window.selectBoxVisible();
    }

    if (el.style.display !== 'none' && typeof window.selectBoxHidden === 'function') {
        window.selectBoxHidden(id);
    }
};

window.AdminShell = {
    initialized: false,

    init() {
        if (this.initialized) {
            return;
        }

        this.initialized = true;

        const menuCookieKey = 'g5_admin_btn_gnb';
        const mobileQuery = window.matchMedia('(max-width: 1023px)');
        const body = document.body;
        const gnb = document.getElementById('gnb');
        const container = document.getElementById('container');
        const desktopToggle = document.getElementById('btn_gnb');
        const mobileToggle = document.getElementById('btn_gnb_mobile');
        const sidebarBackdrop = document.getElementById('adminSidebarBackdrop');
        const profileButton = document.querySelector('.tnb_mb_btn');
        const profileMenu = document.querySelector('.tnb_mb_area');
        const scrollWrap = document.querySelector('#gnb .gnb_menu_scroll_wrap');
        const menuScroll = document.getElementById('gnbMenuScroll');
        const scrollbar = scrollWrap ? scrollWrap.querySelector('.gnb_scrollbar') : null;
        const scrollThumb = scrollWrap ? scrollWrap.querySelector('.gnb_scrollbar_thumb') : null;
        const themeToggle = document.getElementById('admin_theme_toggle');
        const themeToggleIconUse = document.getElementById('admin_theme_toggle_icon_use');
        const navRoot = document.getElementById('adminNavList');
        const scrollTopButton = document.querySelector('.scroll_top');
        let hideScrollbarTimer = null;

        const isMobileViewport = () => mobileQuery.matches;

        const updateMenuScrollbar = () => {
            if (!scrollWrap || !menuScroll || !scrollbar || !scrollThumb) {
                return;
            }

            const scrollHeight = menuScroll.scrollHeight;
            const clientHeight = menuScroll.clientHeight;
            const canScroll = scrollHeight > clientHeight + 1;

            scrollWrap.classList.toggle('is-scrollable', canScroll);

            if (!canScroll) {
                scrollThumb.style.height = '0';
                scrollThumb.style.transform = 'translateY(0)';
                return;
            }

            const trackHeight = scrollbar.getBoundingClientRect().height;
            const thumbHeight = Math.max(28, Math.round(trackHeight * (clientHeight / scrollHeight)));
            const maxThumbTop = Math.max(0, trackHeight - thumbHeight);
            const maxScrollTop = Math.max(1, scrollHeight - clientHeight);
            const thumbTop = Math.round((menuScroll.scrollTop / maxScrollTop) * maxThumbTop);

            scrollThumb.style.height = `${thumbHeight}px`;
            scrollThumb.style.transform = `translateY(${thumbTop}px)`;
        };

        const syncDesktopSidebarState = () => {
            if (!gnb || !container || !desktopToggle) {
                return;
            }

            const collapsed = gnb.classList.contains('gnb_small');
            const desktopCollapsed = !isMobileViewport() && collapsed;
            body.classList.toggle('admin-sidebar-condensed', desktopCollapsed);
            container.classList.toggle('container-small', desktopCollapsed);
            desktopToggle.classList.toggle('btn_gnb_open', desktopCollapsed);
            desktopToggle.setAttribute('aria-pressed', desktopCollapsed ? 'true' : 'false');
        };

        const setDesktopCollapsed = nextCollapsed => {
            try {
                if (nextCollapsed && typeof window.set_cookie === 'function') {
                    window.set_cookie(menuCookieKey, 1, 60 * 60 * 24 * 365);
                } else if (!nextCollapsed && typeof window.delete_cookie === 'function') {
                    window.delete_cookie(menuCookieKey);
                }
            } catch (err) {}

            if (gnb) {
                gnb.classList.toggle('gnb_small', nextCollapsed);
            }
            syncDesktopSidebarState();
        };

        const setMobileSidebar = opened => {
            if (!isMobileViewport()) {
                return;
            }

            body.classList.toggle('admin-sidebar-open', opened);
            body.classList.toggle('overflow-hidden', opened);

            if (mobileToggle) {
                mobileToggle.setAttribute('aria-expanded', opened ? 'true' : 'false');
            }

            if (sidebarBackdrop) {
                sidebarBackdrop.classList.toggle('hidden', !opened);
            }
        };

        const showMenuScrollbar = () => {
            if (!scrollWrap || !scrollWrap.classList.contains('is-scrollable')) {
                return;
            }

            clearTimeout(hideScrollbarTimer);
            scrollWrap.classList.add('is-scrollbar-visible');
        };

        const hideMenuScrollbar = delay => {
            if (!scrollWrap) {
                return;
            }

            clearTimeout(hideScrollbarTimer);
            hideScrollbarTimer = window.setTimeout(() => {
                scrollWrap.classList.remove('is-scrollbar-visible');
            }, delay || 140);
        };

        const syncThemeUI = () => {
            if (!themeToggle || !themeToggleIconUse) {
                return;
            }

            const dark = document.documentElement.getAttribute('data-theme') === 'dark';
            const nextModeLabel = dark ? '라이트 모드' : '다크 모드';
            const iconHref = dark ? '#admin-menu-icon-sun' : '#admin-menu-icon-moon-stars';
            themeToggle.setAttribute('aria-pressed', dark ? 'true' : 'false');
            themeToggle.setAttribute('aria-label', `${nextModeLabel} 전환`);
            themeToggle.setAttribute('title', `${nextModeLabel} 전환`);
            themeToggleIconUse.setAttribute('href', iconHref);
            themeToggleIconUse.setAttribute('xlink:href', iconHref);
        };

        const setNavItemState = (item, opened) => {
            if (!item) {
                return;
            }

            item.classList.toggle('is-open', opened);

            const panel = item.querySelector('.admin-nav-panel');
            if (panel) {
                panel.classList.toggle('hidden', !opened);
            }

            const trigger = item.querySelector('.admin-nav-trigger');
            if (trigger) {
                trigger.setAttribute('aria-expanded', opened ? 'true' : 'false');
            }
        };

        if (profileButton && profileMenu) {
            profileButton.addEventListener('click', () => {
                profileMenu.classList.toggle('hidden');
            });

            document.addEventListener('click', event => {
                if (!event.target.closest('.tnb_li.relative')) {
                    profileMenu.classList.add('hidden');
                }
            });
        }

        if (desktopToggle) {
            desktopToggle.addEventListener('click', () => {
                const nextCollapsed = !(gnb && gnb.classList.contains('gnb_small'));
                setDesktopCollapsed(nextCollapsed);
            });
        }

        if (mobileToggle) {
            mobileToggle.addEventListener('click', event => {
                event.preventDefault();
                event.stopPropagation();

                if (isMobileViewport()) {
                    setMobileSidebar(!body.classList.contains('admin-sidebar-open'));
                    return;
                }

                if (gnb && gnb.classList.contains('gnb_small')) {
                    setDesktopCollapsed(false);
                }
            });
        }

        if (sidebarBackdrop) {
            sidebarBackdrop.addEventListener('click', () => {
                setMobileSidebar(false);
            });
        }

        window.addEventListener('resize', () => {
            if (!isMobileViewport()) {
                body.classList.remove('admin-sidebar-open', 'overflow-hidden');
                if (mobileToggle) {
                    mobileToggle.setAttribute('aria-expanded', 'false');
                }
                if (sidebarBackdrop) {
                    sidebarBackdrop.classList.add('hidden');
                }
            }

            syncDesktopSidebarState();
            updateMenuScrollbar();
        });

        if (gnb) {
            gnb.addEventListener('click', event => {
                if (isMobileViewport() && event.target.closest('a')) {
                    setMobileSidebar(false);
                }
            });
        }

        if (menuScroll) {
            menuScroll.addEventListener('scroll', () => {
                updateMenuScrollbar();
                showMenuScrollbar();
                hideMenuScrollbar(420);
            });
        }

        if (scrollWrap) {
            scrollWrap.addEventListener('mouseenter', () => {
                updateMenuScrollbar();
                showMenuScrollbar();
            });

            scrollWrap.addEventListener('mouseleave', () => {
                hideMenuScrollbar(120);
            });

            scrollWrap.addEventListener('focusin', () => {
                updateMenuScrollbar();
                showMenuScrollbar();
            });

            scrollWrap.addEventListener('focusout', () => {
                window.setTimeout(() => {
                    if (!scrollWrap.contains(document.activeElement)) {
                        hideMenuScrollbar(120);
                    }
                }, 0);
            });
        }

        if (themeToggle) {
            themeToggle.addEventListener('click', () => {
                const dark = document.documentElement.getAttribute('data-theme') === 'dark';
                const next = dark ? 'light' : 'dark';
                document.documentElement.setAttribute('data-theme', next);
                try {
                    localStorage.setItem('g5_admin_theme', next);
                } catch (e) {}
                syncThemeUI();
            });
        }

        if (navRoot) {
            const navItems = Array.prototype.slice.call(navRoot.querySelectorAll('.admin-nav-item'));
            navItems.forEach(item => {
                setNavItemState(item, item.classList.contains('is-open'));
            });

            navRoot.addEventListener('click', event => {
                const trigger = event.target.closest('.admin-nav-trigger');
                if (!trigger || !navRoot.contains(trigger)) {
                    return;
                }

                const activeItem = trigger.closest('.admin-nav-item');
                if (!activeItem) {
                    return;
                }

                const willOpen = !activeItem.classList.contains('is-open');
                navItems.forEach(item => {
                    setNavItemState(item, item === activeItem ? willOpen : false);
                });

                updateMenuScrollbar();
            });
        }

        if (scrollTopButton) {
            scrollTopButton.addEventListener('click', event => {
                event.preventDefault();
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth',
                });
            });
        }

        syncDesktopSidebarState();
        syncThemeUI();
        updateMenuScrollbar();
        window.requestAnimationFrame(updateMenuScrollbar);
    }
};

window.AdminConfigForm = {
    initialAdminId: '',

    init() {
        const form = document.getElementById('fconfigform');
        if (!form) {
            return;
        }

        const certUseField = document.getElementById('cf_cert_use');
        const captchaField = document.getElementById('cf_captcha');
        const adminField = document.getElementById('cf_admin');
        const captchaKey = document.getElementById('captcha_key');

        if (adminField) {
            this.initialAdminId = adminField.value;
            adminField.addEventListener('change', () => this.checkCaptchaOpen());
        }

        if (certUseField) {
            const syncCertFields = () => {
                const hideServices = certUseField.value === '0';
                document.querySelectorAll('.cf_cert_service').forEach(element => {
                    element.classList.toggle('cf_cert_hide', hideServices);
                });
            };

            certUseField.addEventListener('change', syncCertFields);
            syncCertFields();
        }

        if (captchaField) {
            const syncCaptchaFields = () => {
                const isRecaptcha = captchaField.value === 'recaptcha' || captchaField.value === 'recaptcha_inv';
                this.toggleCaptchaFields(isRecaptcha);
            };

            captchaField.addEventListener('change', syncCaptchaFields);
            syncCaptchaFields();
        }

        if (captchaKey) {
            captchaKey.required = false;
            captchaKey.removeAttribute('required');
            captchaKey.classList.remove('required');
        }

        if (window.CommonUI && typeof window.CommonUI.initStickyAnchorTabs === 'function') {
            window.CommonUI.initStickyAnchorTabs({
                tabBarSelector: '#config_tabs_nav',
                tabNavSelector: '#config_tabs_nav',
                tabLinkSelector: "a.js-config-tab-link[href^='#']",
                topbarSelector: '#hd_top',
                heightVarName: '--config-tabs-height',
                scrollGap: 8,
                scrollDuration: 180,
                namespace: 'configTabs',
            });
        }

        form.addEventListener('submit', event => {
            if (!this.submit(form)) {
                event.preventDefault();
            }
        });

        this.showWebpWarning();
        this.checkCaptchaOpen();
    },

    toggleCaptchaFields(isRecaptcha) {
        document.querySelectorAll("[class^='kcaptcha_']").forEach(element => {
            element.style.display = isRecaptcha ? 'none' : '';
        });
    },

    checkCaptchaOpen() {
        let isChanged = false;
        const adminField = document.getElementById('cf_admin');
        const wrap = document.getElementById('config_captcha_wrap');
        const captcha = document.getElementById('captcha');
        const tooltipId = 'mp_captcha_tooltip';
        const children = wrap ? wrap.firstElementChild : null;
        const isInvisibleRecaptcha = captcha ? captcha.classList.contains('invisible_recaptcha') : false;

        if (adminField && adminField.value) {
            isChanged = isChanged || adminField.value !== this.initialAdminId;
        }

        if (!wrap) {
            return isChanged;
        }

        wrap.hidden = !isChanged;
        wrap.style.display = isChanged ? '' : 'none';

        if (isChanged && !isInvisibleRecaptcha) {
            wrap.style.marginTop = '1em';
            if (!document.getElementById(tooltipId)) {
                const tooltip = document.createElement('p');
                tooltip.id = tooltipId;
                tooltip.style.fontSize = '0.95em';
                tooltip.style.letterSpacing = '-0.1em';
                tooltip.textContent = '중요정보를 수정할 경우 캡챠를 입력해야 합니다.';
                if (children) {
                    children.insertAdjacentElement('afterend', tooltip);
                } else {
                    wrap.appendChild(tooltip);
                }
            }
        } else {
            const existingTooltip = document.getElementById(tooltipId);
            if (existingTooltip && !isInvisibleRecaptcha) {
                existingTooltip.remove();
            }
        }

        return isChanged;
    },

    submit(form) {
        const currentUserIp = form.dataset.currentUserIp || '';
        const interceptIpField = form.elements.cf_intercept_ip;
        const interceptIpValue = interceptIpField ? interceptIpField.value : '';

        if (this.checkCaptchaOpen()) {
            const captchaWrap = document.getElementById('config_captcha_wrap');
            if (captchaWrap) {
                window.scrollTo(0, captchaWrap.getBoundingClientRect().top + window.pageYOffset);
            }

            if (typeof window.chk_captcha === 'function' && !window.chk_captcha()) {
                return false;
            }
        }

        if (interceptIpValue && currentUserIp) {
            const interceptIps = interceptIpValue.split('\n');

            for (let i = 0; i < interceptIps.length; i++) {
                const interceptIp = interceptIps[i].trim();
                if (!interceptIp) {
                    continue;
                }

                const pattern = interceptIp.replace('.', '\\.').replace('+', '[0-9\\.]+');
                const re = new RegExp(pattern);
                if (re.test(currentUserIp)) {
                    alert(`현재 접속 IP : ${currentUserIp} 가 차단될수 있기 때문에, 다른 IP를 입력해 주세요.`);
                    return false;
                }
            }
        }

        form.action = './config_form_update.php';
        return true;
    },

    showWebpWarning() {
        const form = document.getElementById('fconfigform');
        if (!form || form.dataset.webpWarning !== '1') {
            return;
        }

        alert('이 서버는 webp 이미지를 지원하고 있지 않습니다.\n이미지 업로드 확장자에서 webp 확장자를 제거해 주십시오.\n제거하지 않으면 이미지와 관련된 오류가 발생할 수 있습니다.');

        const imageExtensionField = document.getElementById('cf_image_extension');
        if (imageExtensionField) {
            imageExtensionField.focus();
        }
    }
};

/** 팝업 관리 모듈 */
window.PopupManager = {
    init() {
        const overlay = document.getElementById('popupOverlay');
        if (overlay) {
            overlay.addEventListener('click', event => {
                if (event.target === overlay) {
                    this.close('popupOverlay');
                }
            });
        }

        document.querySelectorAll('[data-popup-close]').forEach(button => {
            button.addEventListener('click', () => {
                this.close(button.dataset.popupClose);
            });
        });
    },

    open(id, options = {}) {
        const el = document.getElementById(id);
        if (!el) {
            return;
        }

        el.classList.remove('is-hidden');

        if (!options.disableOutsideClose) {
            this.bindOutsideClickClose(id);
            return;
        }

        this.unbindOutsideClickClose(id);
    },

    close(id) {
        const el = document.getElementById(id);
        if (el) {
            el.classList.add('is-hidden');
        }
    },

    toggle(id) {
        const el = document.getElementById(id);
        if (el) {
            el.classList.toggle('is-hidden');
        }
    },

    bindOutsideClickClose(id) {
        const el = document.getElementById(id);
        if (!el) {
            return;
        }

        el.onclick = () => this.close(id);
    },

    unbindOutsideClickClose(id) {
        const el = document.getElementById(id);
        if (!el) {
            return;
        }

        el.onclick = null;
    },

    render(title, body, footer = '', options = {}) {
        const titleEl = document.getElementById('popupTitle');
        const bodyEl = document.getElementById('popupBody');
        const footerEl = document.getElementById('popupFooter');

        if (titleEl) {
            titleEl.textContent = title;
        }
        if (bodyEl) {
            setHtml(bodyEl, body);
        }
        if (footerEl) {
            setHtml(footerEl, footer);
        }

        this.open('popupOverlay', options);
    }
};

window.AdminSelection = {
    checkAll(target) {
        const chkboxes = document.getElementsByName('chk[]');
        let chkall;

        if (target && target.tagName === 'FORM') {
            chkall = target.querySelector('input[name="chkall"]');
        } else if (target && target.type === 'checkbox') {
            chkall = target;
        }

        if (!chkall) {
            return;
        }

        for (const checkbox of chkboxes) {
            checkbox.checked = chkall.checked;
        }
    },

    isChecked(elementsName) {
        const chk = document.getElementsByName(elementsName);

        for (let i = 0; i < chk.length; i++) {
            if (chk[i].checked) {
                return true;
            }
        }

        return false;
    },

    submitBulkAction(form, action) {
        let text;

        if (action === 'update') {
            form.action = list_update_php;
            text = '수정';
        } else if (action === 'delete') {
            form.action = list_delete_php;
            text = '삭제';
        } else {
            return;
        }

        if (!this.isChecked('chk[]')) {
            alert(text + '할 자료를 하나 이상 선택하세요.');
            return;
        }

        if (action === 'delete' && !confirm('선택한 자료를 정말 삭제 하시겠습니까?')) {
            return;
        }

        form.submit();
    }
};

window.AdminSecurity = {
    buildAjaxTokenPayload() {
        return new URLSearchParams({
            admin_csrf_token_key: typeof g5_admin_csrf_token_key !== 'undefined' ? g5_admin_csrf_token_key : ''
        }).toString();
    },

    getAjaxToken() {
        let token = '';
        const xhr = new XMLHttpRequest();
        xhr.open('POST', g5_admin_url + '/ajax.token.php', false);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
        xhr.send(this.buildAjaxTokenPayload());

        if (xhr.status < 200 || xhr.status >= 400) {
            return token;
        }

        let data = {};
        try {
            data = JSON.parse(xhr.responseText || '{}');
        } catch (e) {
            data = {};
        }

        if (data.error) {
            alert(data.error);
            if (data.url) {
                document.location.href = data.url;
            }

            return false;
        }

        token = data.token || '';
        return token;
    },

    ensureTokenField(form) {
        let tokenField = form.querySelector('input[name=token]');

        if (tokenField) {
            return tokenField;
        }

        tokenField = document.createElement('input');
        tokenField.type = 'hidden';
        tokenField.name = 'token';
        form.prepend(tokenField);

        return tokenField;
    },

    injectSubmitToken(event) {
        const submitButton = event.target.closest("form input[type='submit'], form button[type='submit']");
        if (!submitButton || !submitButton.form) {
            return;
        }

        const token = this.getAjaxToken();
        if (!token) {
            alert('토큰 정보가 올바르지 않습니다.');
            event.preventDefault();
            return;
        }

        this.ensureTokenField(submitButton.form).value = token;
    },

    deleteConfirm(el) {
        if (!confirm('한번 삭제한 자료는 복구할 방법이 없습니다.\n\n정말 삭제하시겠습니까?')) {
            return false;
        }

        const token = this.getAjaxToken();
        const href = el.href.replace(/&token=.+$/g, '');
        if (!token) {
            alert('토큰 정보가 올바르지 않습니다.');
            return false;
        }

        el.href = href + '&token=' + token;
        return true;
    },

    deleteConfirmMessage(message) {
        return confirm(message);
    }
};

window.AdminMemberList = {
    init() {
        const form = document.querySelector('[data-admin-member-list]');
        if (!form) {
            return;
        }

        const deleteForm = document.getElementById('member_delete_form');
        const checkAllInput = form.querySelector('#chkall');
        const placeSideview = dropdown => {
            if (!dropdown || !dropdown.classList.contains('hs-dropdown-open')) {
                return;
            }

            const toggle = dropdown.querySelector('.hs-dropdown-toggle');
            const menu = dropdown.querySelector('.hs-dropdown-menu');
            if (!toggle || !menu) {
                return;
            }

            menu.style.display = 'block';
            menu.style.position = 'fixed';
            menu.style.minWidth = '8.5rem';
            menu.style.maxWidth = '18rem';

            const toggleRect = toggle.getBoundingClientRect();
            const menuRect = menu.getBoundingClientRect();
            const viewportGap = 8;
            let top = Math.round(toggleRect.bottom + 10);
            let left = Math.round(toggleRect.left);

            if (left + menuRect.width > window.innerWidth - viewportGap) {
                left = Math.max(viewportGap, Math.round(toggleRect.right - menuRect.width));
            }

            if (top + menuRect.height > window.innerHeight - viewportGap) {
                const topAbove = Math.round(toggleRect.top - menuRect.height - 10);
                top = topAbove >= viewportGap
                    ? topAbove
                    : Math.max(viewportGap, window.innerHeight - menuRect.height - viewportGap);
            }

            menu.style.left = left + 'px';
            menu.style.top = top + 'px';
        };

        form.addEventListener('submit', event => {
            if (!window.AdminSelection.isChecked('chk[]')) {
                alert('선택삭제 하실 항목을 하나 이상 선택하세요.');
                event.preventDefault();
                return;
            }

            if (!confirm('선택한 자료를 정말 삭제하시겠습니까?')) {
                event.preventDefault();
            }
        });

        form.addEventListener('click', event => {
            const submitButton = event.target.closest('input[type="submit"][name="act_button"], button[type="submit"][name="act_button"]');
            if (submitButton) {
                document.pressed = submitButton.value;
            }

            const deleteButton = event.target.closest('[data-member-delete-id]');
            if (!deleteButton || !deleteForm) {
                return;
            }

            if (!confirm('이 회원을 삭제하시겠습니까?')) {
                return;
            }

            deleteForm.elements.mb_id.value = deleteButton.dataset.memberDeleteId || '';
            deleteForm.submit();
        });

        form.addEventListener('ui.dropdown.open', event => {
            placeSideview(event.target);
        });

        if (checkAllInput) {
            checkAllInput.addEventListener('change', () => {
                window.AdminSelection.checkAll(checkAllInput);
            });
        }

        const repositionSideviews = () => {
            form.querySelectorAll('.hs-dropdown.hs-dropdown-open').forEach(placeSideview);
        };

        window.addEventListener('resize', repositionSideviews);
        window.addEventListener('scroll', repositionSideviews, true);
    }
};

window.AdminMemberExport = {
    init() {
        const root = document.querySelector('[data-admin-member-export]');
        if (!root) {
            return;
        }

        const form = root.querySelector('[data-admin-member-export-form]');
        const downloadButton = root.querySelector('#btnExcelDownload');
        const adRangeToggle = form ? form.querySelector('input[name="ad_range_only"]') : null;
        const adRangeType = form ? form.querySelector('#ad_range_type') : null;
        const customPeriod = root.querySelector('[data-admin-member-export-custom-period]');
        const channelRow = root.querySelector('[data-admin-member-export-channel-row]');
        let eventSource = null;

        const config = {
            streamUrl: root.dataset.streamUrl || '',
            popupProgressTitle: root.dataset.popupProgressTitle || '엑셀 다운로드 진행 중',
            popupDoneTitle: root.dataset.popupDoneTitle || '엑셀 파일 다운로드 완료',
            closeConfirmMessage: root.dataset.closeConfirmMessage || '엑셀 다운로드가 진행 중입니다.\n정말 중지하시겠습니까?',
            downloadStoppedMessage: root.dataset.downloadStoppedMessage || '엑셀 다운로드가 중단되었습니다.',
            downloadFailedSummary: root.dataset.downloadFailedSummary || '엑셀 파일 다운로드 실패',
            downloadFailedMessage: root.dataset.downloadFailedMessage || '엑셀 파일 다운로드에 실패하였습니다',
            estimatedSecondsMultiplier: Number(root.dataset.estimatedSecondsMultiplier || 0.00144),
            estimatedSecondsMin: Number(root.dataset.estimatedSecondsMin || 5),
            environmentReady: root.dataset.environmentReady === '1',
            environmentError: root.dataset.environmentError || '',
        };

        const closePreviousEventSource = () => {
            if (eventSource) {
                eventSource.close();
                eventSource = null;
            }
        };

        const toggleAdRangeSections = () => {
            const adRangeEnabled = !!(adRangeToggle && adRangeToggle.checked);
            root.querySelectorAll('.ad_range_wrap').forEach(element => {
                element.classList.toggle('is-hidden', !adRangeEnabled);
            });

            if (customPeriod) {
                const showCustomPeriod = adRangeEnabled && adRangeType && adRangeType.value === 'custom_period';
                customPeriod.classList.toggle('is-hidden', !showCustomPeriod);
            }

            if (channelRow) {
                const showChannelRow = adRangeEnabled && adRangeType && ['month_confirm', 'custom_period'].includes(adRangeType.value);
                channelRow.classList.toggle('is-hidden', !showChannelRow);
            }
        };

        const autoSubmitFilterForm = event => {
            if (!form) {
                return;
            }

            const target = event.target;
            if (!target || !form.contains(target)) {
                return;
            }

            if (event.type === 'keydown') {
                if (event.key !== 'Enter') {
                    return;
                }

                event.preventDefault();
                form.submit();
                return;
            }

            if (target.type === 'date' && event.type !== 'blur') {
                return;
            }

            if (target.type !== 'date' && event.type !== 'change') {
                return;
            }

            form.submit();
        };

        const buildDownloadParams = () => {
            const formData = new FormData(form);
            const params = new URLSearchParams(formData);
            params.append('mode', 'start');
            return params.toString();
        };

        const triggerAutoDownload = (url, filename) => {
            const anchor = document.createElement('a');
            anchor.href = url;
            anchor.download = filename;
            document.body.appendChild(anchor);
            anchor.click();
            document.body.removeChild(anchor);
        };

        const handlePopupCloseWithConfirm = event => {
            if (eventSource) {
                if (!confirm(config.closeConfirmMessage)) {
                    event.preventDefault();
                    return;
                }

                closePreviousEventSource();
                alert(config.downloadStoppedMessage);
            }

            PopupManager.close('popupOverlay');
        };

        const showDownloadPopup = () => {
            const bodyHTML = `
                <div class="excel-download-progress">
                    <div class="progress-desc">
                        <p class="progress-summary">총 <strong>0</strong>개 파일로 분할됩니다</p>
                        <p class="progress-message"><strong>(0 / 0)</strong> 파일 다운로드 중</p>
                        <p class="progress-error"></p>
                    </div>
                    <div class="progress-spinner">
                        <div class="spinner"></div>
                        <p class="loading-message">
                            엑셀 파일을 생성 중입니다. 잠시만 기다려주세요.<br>
                            현재 데이터 기준으로 <strong id="estimatedTimeText"></strong> 정도 소요될 수 있습니다.<br>
                            <strong>페이지를 벗어나거나 닫으면 다운로드가 중단</strong>되니, 작업 완료까지 기다려 주세요.
                        </p>
                    </div>
                    <div class="progress-box">
                        <div class="progress-download-box"></div>
                    </div>
                </div>
            `;

            PopupManager.render(config.popupProgressTitle, bodyHTML, '', { disableOutsideClose: true });

            const closeButton = document.querySelector('.popup-close-btn');
            if (closeButton) {
                closeButton.removeAttribute('onclick');
                closeButton.addEventListener('click', handlePopupCloseWithConfirm);
            }
        };

        const handleProgressUpdate = event => {
            const data = JSON.parse(event.data);
            const { status, downloadType, message, total, totalChunks, currentChunk, zipFile, files, filePath } = data;
            const titleEl = document.getElementById('popupTitle');
            const summaryEl = document.querySelector('.progress-summary');
            const messageEl = document.querySelector('.progress-message');
            const spinnerEl = document.querySelector('.progress-spinner');
            const resultEl = document.querySelector('.loading-message');
            const downloadBoxEl = document.querySelector('.progress-download-box');
            const errorEl = document.querySelector('.progress-error');

            if (!summaryEl || !messageEl || !downloadBoxEl || !errorEl) {
                return;
            }

            if (status === 'progress') {
                summaryEl.innerHTML = `총 <strong>${totalChunks}</strong>개 파일로 ${downloadType === 2 ? '분할 생성됩니다' : '다운로드됩니다'} (총 ${total.toLocaleString('ko-KR')}건)`;
                messageEl.innerHTML = downloadType === 2 ? `<strong>(${currentChunk} / ${totalChunks})</strong> 파일 생성 중` : '엑셀 파일 생성 중';

                const sec = Math.max(config.estimatedSecondsMin, Math.ceil(total * config.estimatedSecondsMultiplier));
                const estimatedText = `예상 처리 시간은 약 ${sec >= 60 ? `${Math.floor(sec / 60)}분 ${sec % 60}초` : `${sec}초`}`;
                const estimatedTimeText = document.getElementById('estimatedTimeText');
                if (estimatedTimeText) {
                    estimatedTimeText.innerText = estimatedText;
                }
                return;
            }

            if (status === 'zipping') {
                summaryEl.innerHTML = `총 <strong>${totalChunks}</strong>개 파일이 압축파일로 생성됩니다`;
                messageEl.innerHTML = `<strong>${totalChunks}</strong> 파일 압축하는 중`;
                return;
            }

            if (status === 'zippingError') {
                errorEl.innerHTML = message;
                return;
            }

            if (status === 'error') {
                const parts = message.split(/<br\s*\/?>/i);
                summaryEl.innerHTML = config.downloadFailedSummary;
                messageEl.innerHTML = parts[0] || '';
                errorEl.innerHTML = parts.slice(1).join('<br>') || '';
                if (resultEl) {
                    resultEl.innerHTML = '';
                }
                spinnerEl?.classList.add('is-hidden');
                closePreviousEventSource();
                return;
            }

            if (status === 'done') {
                closePreviousEventSource();
                if (titleEl) {
                    titleEl.textContent = config.popupDoneTitle;
                }
                messageEl.innerHTML = `<strong>총 ${total.toLocaleString('ko-KR')}건의 데이터 다운로드가 완료되었습니다!</strong>`;
                spinnerEl?.classList.add('is-hidden');

                let html = '<p>* 자동으로 다운로드가 되지 않았다면 아래 버튼을 클릭해주세요.</p>';

                if (zipFile) {
                    const url = `${filePath}/${zipFile}`;
                    html += `<a href="${url}" class="btn btn-tertiary" download>압축파일 다운로드</a>`;
                    downloadBoxEl.innerHTML = html;
                    triggerAutoDownload(url, zipFile);
                    return;
                }

                if (files?.length) {
                    files.forEach((file, index) => {
                        const url = `${filePath}/${file}`;
                        html += `<a class="btn btn-tertiary" href="${url}" download>엑셀파일 다운로드 ${index + 1}</a>`;
                    });
                    downloadBoxEl.innerHTML = html;

                    if (files.length === 1) {
                        const url = `${filePath}/${files[0]}`;
                        triggerAutoDownload(url, files[0]);
                    } else {
                        summaryEl.innerHTML = `총 <strong>${totalChunks}</strong>개 파일이 생성되었습니다. 아래 버튼을 눌러 다운로드 받아주세요.`;
                    }
                }
            }
        };

        const handleDownloadError = event => {
            const errorMessage = event?.message || event?.data || '알 수 없는 오류가 발생했습니다.';
            const summaryEl = document.querySelector('.progress-summary');
            const messageEl = document.querySelector('.progress-message');
            const errorEl = document.querySelector('.progress-error');
            const loadingMessage = document.querySelector('.loading-message');
            const spinnerEl = document.querySelector('.progress-spinner');

            if (summaryEl) {
                summaryEl.innerHTML = config.downloadFailedSummary;
            }
            if (messageEl) {
                messageEl.innerHTML = config.downloadFailedMessage;
            }
            if (errorEl) {
                errorEl.innerHTML = errorMessage;
            }
            if (loadingMessage) {
                loadingMessage.innerHTML = '';
            }
            spinnerEl?.classList.add('is-hidden');
            closePreviousEventSource();
        };

        const startExcelDownload = () => {
            if (!config.environmentReady) {
                alert(config.environmentError || config.downloadFailedMessage);
                return;
            }

            closePreviousEventSource();
            showDownloadPopup();

            const query = buildDownloadParams();
            eventSource = new EventSource(`${config.streamUrl}?${query}`);
            eventSource.onmessage = handleProgressUpdate;
            eventSource.onerror = handleDownloadError;
        };

        toggleAdRangeSections();
        if (adRangeToggle) {
            adRangeToggle.addEventListener('change', toggleAdRangeSections);
        }
        if (adRangeType) {
            adRangeType.addEventListener('change', toggleAdRangeSections);
        }
        if (form) {
            form.addEventListener('change', autoSubmitFilterForm);
            form.addEventListener('blur', autoSubmitFilterForm, true);
            form.addEventListener('keydown', autoSubmitFilterForm);
        }
        if (downloadButton) {
            downloadButton.addEventListener('click', startExcelDownload);
        }
    }
};

window.AdminMemberForm = {
    init() {
        const root = document.querySelector('[data-admin-member-form]');
        const form = document.getElementById('fmember');
        if (!root || !form) {
            return;
        }

        const passwordField = document.getElementById('mb_password');
        const captchaWrap = document.getElementById('mb_password_captcha_wrap');
        const captchaKey = document.getElementById('captcha_key');
        let passwordFieldUnlocked = false;

        const postSync = url => {
            const xhr = new XMLHttpRequest();
            xhr.open('POST', url, false);
            xhr.send(null);

            return {
                ok: xhr.status >= 200 && xhr.status < 400,
                status: xhr.status,
                url,
            };
        };

        const refreshMemberPasswordCaptcha = () => {
            let candidates = [];
            let captchaUrl = window.g5_captcha_url || '';
            const adminBase = (window.g5_admin_url || '').replace(/\/+$/, '').replace(/\/adm$/, '');
            const pathBase = window.location.origin + window.location.pathname.replace(/\/adm\/.*$/, '');
            let resolved = '';

            if (captchaUrl) {
                candidates.push(captchaUrl);
            }
            if (window.g5_url) {
                candidates.push(window.g5_url.replace(/\/+$/, '') + '/plugin/kcaptcha');
            }
            if (adminBase) {
                candidates.push(adminBase + '/plugin/kcaptcha');
            }
            if (pathBase && /\/g5aif$/i.test(pathBase)) {
                candidates.push(pathBase + '/plugin/kcaptcha');
            }

            candidates = candidates.filter((url, index) => candidates.indexOf(url) === index);
            if (!candidates.length) {
                return;
            }

            for (const candidate of candidates) {
                captchaUrl = candidate;
                if (/^https:/i.test(window.location.protocol) && /^http:\/\//i.test(captchaUrl)) {
                    captchaUrl = captchaUrl.replace(/^http:/i, 'https:');
                }

                try {
                    if (new URL(captchaUrl, window.location.href).origin !== window.location.origin) {
                        continue;
                    }
                } catch (error) {
                    continue;
                }

                const response = postSync(captchaUrl + '/kcaptcha_session.php');
                if (!resolved && response.ok) {
                    resolved = response.url.replace(/\/kcaptcha_session\.php(?:\?.*)?$/, '');
                }

                if (resolved) {
                    break;
                }
            }

            if (!resolved) {
                return;
            }

            const captchaImage = document.getElementById('captcha_img');
            if (!captchaImage) {
                return;
            }

            captchaImage.style.display = 'inline-block';
            captchaImage.style.verticalAlign = 'middle';
            captchaImage.style.marginRight = '8px';
            captchaImage.src = resolved + '/kcaptcha_image.php?t=' + Date.now();
        };

        const hideMemberPasswordCaptcha = () => {
            const captcha = document.getElementById('captcha');
            const isInvisibleRecaptcha = captcha ? captcha.classList.contains('invisible_recaptcha') : false;

            if (captchaWrap) {
                captchaWrap.style.display = 'none';
            }
            if (!isInvisibleRecaptcha) {
                const tooltip = document.getElementById('mp_captcha_tooltip');
                if (tooltip) {
                    tooltip.remove();
                }
            }
        };

        const unlockMemberPasswordField = () => {
            if (passwordFieldUnlocked || !passwordField) {
                return;
            }

            passwordFieldUnlocked = true;
            passwordField.readOnly = false;
            passwordField.removeAttribute('readonly');
        };

        const toggleMemberPasswordCaptcha = () => {
            const captcha = document.getElementById('captcha');
            const parent = passwordField ? passwordField.parentElement : null;
            const captchaImage = document.getElementById('captcha_img');
            const isInvisibleRecaptcha = captcha ? captcha.classList.contains('invisible_recaptcha') : false;
            const wasHidden = !captchaWrap || captchaWrap.style.display === 'none' || window.getComputedStyle(captchaWrap).display === 'none';

            if (!captchaWrap || !passwordField) {
                return;
            }

            if (passwordField.value) {
                captchaWrap.style.display = '';
                if (wasHidden || ((captchaImage && captchaImage.getAttribute('src')) || '').indexOf('dot.gif') !== -1) {
                    refreshMemberPasswordCaptcha();
                }

                if (!isInvisibleRecaptcha) {
                    captchaWrap.style.marginTop = '1em';
                    if (!document.getElementById('mp_captcha_tooltip') && parent) {
                        const tooltip = document.createElement('span');
                        tooltip.id = 'mp_captcha_tooltip';
                        tooltip.style.fontSize = '0.95em';
                        tooltip.style.letterSpacing = '-0.1em';
                        tooltip.innerHTML = '비밀번호를 수정할 경우 캡챠를 입력해야 합니다.';
                        parent.appendChild(tooltip);
                    }
                }
            } else {
                hideMemberPasswordCaptcha();
            }
        };

        const initializeDateToggle = toggle => {
            const targetName = toggle.dataset.dateToggleTarget || '';
            const targetField = targetName ? form.elements[targetName] : null;
            if (!targetField) {
                return;
            }

            toggle.addEventListener('change', () => {
                if (targetField.value === targetField.defaultValue) {
                    targetField.value = toggle.value;
                    return;
                }

                targetField.value = targetField.defaultValue;
            });
        };

        form.addEventListener('submit', event => {
            if (passwordField && passwordField.value && typeof window.chk_captcha === 'function' && !window.chk_captcha()) {
                event.preventDefault();
            }
        });

        if (captchaKey) {
            captchaKey.required = false;
            captchaKey.removeAttribute('required');
            captchaKey.classList.remove('required');
        }

        if (window.CommonUI && typeof window.CommonUI.initStickyAnchorTabs === 'function') {
            window.CommonUI.initStickyAnchorTabs({
                tabBarSelector: '#member_tabs_nav',
                tabNavSelector: '#member_tabs_nav',
                tabLinkSelector: 'a.js-member-tab-link[href^="#"]',
                topbarSelector: '#hd_top',
                heightVarName: '--config-tabs-height',
                scrollGap: 8,
                scrollDuration: 180,
                namespace: 'memberTabs',
            });
        }

        hideMemberPasswordCaptcha();
        root.querySelectorAll('[data-date-toggle-target]').forEach(initializeDateToggle);

        if (!passwordField) {
            return;
        }

        ['focus', 'pointerdown', 'keydown'].forEach(eventName => {
            passwordField.addEventListener(eventName, unlockMemberPasswordField);
        });

        ['input', 'keyup', 'change'].forEach(eventName => {
            passwordField.addEventListener(eventName, () => {
                if (!passwordFieldUnlocked && passwordField.value) {
                    passwordField.value = '';
                    hideMemberPasswordCaptcha();
                    return;
                }

                toggleMemberPasswordCaptcha();
            });
        });

        window.setTimeout(() => {
            if (!passwordFieldUnlocked && passwordField.value) {
                passwordField.value = '';
                hideMemberPasswordCaptcha();
            }
        }, 150);
    }
};

function check_all(target) {
    window.AdminSelection.checkAll(target);
}

function btn_check(form, action) {
    window.AdminSelection.submitBulkAction(form, action);
}

function is_checked(elementsName) {
    return window.AdminSelection.isChecked(elementsName);
}

function delete_confirm(el) {
    return window.AdminSecurity.deleteConfirm(el);
}

function delete_confirm2(message) {
    return window.AdminSecurity.deleteConfirmMessage(message);
}

function get_ajax_token() {
    return window.AdminSecurity.getAjaxToken();
}

document.addEventListener('DOMContentLoaded', function() {
    window.AdminShell.init();
    window.PopupManager.init();
    window.AdminConfigForm.init();
    window.AdminMemberList.init();
    window.AdminMemberExport.init();
    window.AdminMemberForm.init();
    document.addEventListener('click', function(event) {
        window.AdminSecurity.injectSubmitToken(event);
    });
});

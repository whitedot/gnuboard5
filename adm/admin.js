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

/** 팝업 관리 모듈 */
window.PopupManager = {
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
    window.AdminMemberList.init();
    window.AdminMemberExport.init();
    window.AdminMemberForm.init();
    document.addEventListener('click', function(event) {
        window.AdminSecurity.injectSubmitToken(event);
    });
});

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

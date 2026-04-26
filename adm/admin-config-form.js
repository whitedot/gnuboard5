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

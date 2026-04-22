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
    document.addEventListener('click', function(event) {
        window.AdminSecurity.injectSubmitToken(event);
    });
});

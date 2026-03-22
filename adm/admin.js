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

      if (target) target.classList.remove('is-hidden');

      options.onChange?.(tabName, target);
    });
  });
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

    var xhr = new XMLHttpRequest();
    xhr.open("POST", g5_admin_url + "/ajax.token.php", false);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded; charset=UTF-8");
    xhr.send(new URLSearchParams({
        admin_csrf_token_key: admin_csrf_token_key
    }).toString());

    if (xhr.status >= 200 && xhr.status < 400) {
        var data = {};

        try {
            data = JSON.parse(xhr.responseText || "{}");
        } catch (e) {
            data = {};
        }

        if (data.error) {
            alert(data.error);
            if (data.url)
                document.location.href = data.url;

            return false;
        }

        token = data.token || "";
    }

    return token;
}

document.addEventListener("DOMContentLoaded", function() {
    document.addEventListener("click", function(event) {
        var submitButton = event.target.closest("form input[type='submit'], form button[type='submit']");
        if (!submitButton) {
            return;
        }

        var f = submitButton.form;
        if (!f) {
            return;
        }

        var token = get_ajax_token();

        if(!token) {
            alert("토큰 정보가 올바르지 않습니다.");
            event.preventDefault();
            return;
        }

        var tokenField = f.querySelector("input[name=token]");

        if (!tokenField) {
            tokenField = document.createElement("input");
            tokenField.type = "hidden";
            tokenField.name = "token";
            f.prepend(tokenField);
        }

        tokenField.value = token;
    });
});

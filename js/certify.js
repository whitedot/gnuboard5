function ensureVerificationHashField() {
    var existingField = document.querySelector("input[name=veri_up_hash]");
    var certNoField = document.querySelector("input[name=cert_no]");

    if (existingField || !certNoField || !certNoField.parentNode) {
        return;
    }

    var hiddenField = document.createElement("input");
    hiddenField.type = "hidden";
    hiddenField.name = "veri_up_hash";
    hiddenField.value = "";
    certNoField.insertAdjacentElement("afterend", hiddenField);
}

function wrapFormInCertInfo(form) {
    if (!form || document.getElementById("cert_info")) {
        return document.getElementById("cert_info");
    }

    var wrapper = document.createElement("div");
    wrapper.id = "cert_info";
    form.parentNode.insertBefore(wrapper, form);
    wrapper.appendChild(form);

    return wrapper;
}

function ensureTempForm(container) {
    var tempForm = document.forms.form_temp;
    if (tempForm || !container) {
        return tempForm;
    }

    tempForm = document.createElement("form");
    tempForm.name = "form_temp";
    tempForm.method = "post";
    container.appendChild(tempForm);

    return tempForm;
}

function findEventForm(event) {
    if (!event || !event.target) {
        return null;
    }

    return event.target.form || event.target.closest("form");
}

// 본인확인 인증창 호출
function certify_win_open(type, url, event) {
    if (typeof event == "undefined") {
        event = window.event;
    }

    if(type == "kcp-hp")
    {
        ensureVerificationHashField();

        // iframe에서 세션공유 문제가 있어서 더 이상 iframe 을 사용하지 않습니다.
        var use_iframe = false;

        if(use_iframe && (navigator.userAgent.indexOf("Android") > -1 || navigator.userAgent.indexOf("iPhone") > -1))
        {
            var form = findEventForm(event);

            if (!form) {
                return;
            }

            var certInfo = wrapFormInCertInfo(form);
            var tempForm = ensureTempForm(certInfo);
            var iframe = document.getElementById("kcp_cert");

            if (iframe) {
                iframe.remove();
            }

            iframe = document.createElement("iframe");
            iframe.id = "kcp_cert";
            iframe.name = "kcp_cert";
            iframe.width = "100%";
            iframe.height = "700";
            iframe.frameBorder = "0";
            iframe.scrolling = "yes";
            iframe.style.display = "none";
            certInfo.insertAdjacentElement("afterend", iframe);

            tempForm.target = "kcp_cert";
            tempForm.action = url;

            certInfo.style.display = "none";
            iframe.style.display = "";

            tempForm.submit();
        }
        else
        {
            var width  = 410;
            var height = 500;

            var leftpos = screen.width  / 2 - ( width  / 2 );
            var toppos  = screen.height / 2 - ( height / 2 );

            var winopts  = "width=" + width   + ", height=" + height + ", toolbar=no,status=no,statusbar=no,menubar=no,scrollbars=no,resizable=no";
            var position = ",left=" + leftpos + ", top="    + toppos;
            window.open(url, "auth_popup", winopts + position);
        }
    }
    else if(type == "lg-hp")
    {

        if( g5_is_mobile )
        {
            var mobileForm = findEventForm(event);
            var lgu_cert = "lgu_cert";

            if (!mobileForm) {
                return;
            }

            var certWrap = wrapFormInCertInfo(mobileForm);
            ensureTempForm(certWrap);

            var lguFrame = document.getElementById(lgu_cert);
            if (lguFrame) {
                lguFrame.remove();
            }

            lguFrame = document.createElement("iframe");
            lguFrame.id = lgu_cert;
            lguFrame.name = lgu_cert;
            lguFrame.width = "100%";
            lguFrame.src = url;
            lguFrame.height = "700";
            lguFrame.frameBorder = "0";
            lguFrame.scrolling = "no";
            lguFrame.style.display = "none";
            certWrap.insertAdjacentElement("afterend", lguFrame);

            certWrap.style.display = "none";
            lguFrame.style.display = "";

        } else {
            var popupWidth = 640;
            var popupHeight = 660;

            var leftpos = screen.width  / 2 - ( popupWidth  / 2 );
            var toppos  = screen.height / 2 - ( popupHeight / 2 );

            var popupWindow = window.open(url, "auth_popup", "left=" + leftpos + ", top=" + toppos + ", width=" + popupWidth + ", height=" + popupHeight + ", scrollbar=yes");
            if (popupWindow) {
                popupWindow.focus();
            }
        }
    }
}

// 인증체크
function cert_confirm() {

    var type;
    var val = document.fregisterform.cert_type.value;

    switch(val) {
        case "simple":
            type = "간편인증";
            break;
        case "ipin":
            type = "아이핀";
            break;
        case "hp":
            type = "휴대폰";
            break;
        default:
            return true;
    }

    if(confirm("이미 " + type + "으로 본인확인을 완료하셨습니다.\n\n이전 인증을 취소하고 다시 인증하시겠습니까?"))
        return true;
    else
        return false;
}

function call_sa(url) {
    var popupWindow = popup_center();
    if(popupWindow != undefined && popupWindow != null) {
        popupWindow.location.href = url;
    }
}

function popup_center() {
    var popupWidth = 400;
    var popupHeight = 620;
    var xPos = (document.body.offsetWidth / 2) - (popupWidth / 2);
    xPos += window.screenLeft;
    return window.open("", "sa_popup", "width=" + popupWidth + ", height=" + popupHeight + ", left=" + xPos + ", menubar=yes, status=yes, titlebar=yes, resizable=yes");
}

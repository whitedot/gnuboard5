function syncCaptchaRequest(url, data) {
    var xhr = new XMLHttpRequest();
    xhr.open("POST", url, false);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded; charset=UTF-8");
    xhr.send(data ? new URLSearchParams(data).toString() : null);

    if (xhr.status >= 200 && xhr.status < 400) {
        return xhr.responseText;
    }

    return "";
}

function initKcaptcha() {
    var mp3_url = "";
    var reloadButton = document.getElementById("captcha_reload");
    var mp3Button = document.getElementById("captcha_mp3");

    if (!reloadButton) {
        return;
    }

    if (reloadButton.dataset.kcaptchaBound === "true") {
        return;
    }

    reloadButton.dataset.kcaptchaBound = "true";
    if (mp3Button) {
        mp3Button.dataset.kcaptchaBound = "true";
    }

    function reloadCaptcha() {
        syncCaptchaRequest(g5_captcha_url + "/kcaptcha_session.php");

        var captchaImage = document.getElementById("captcha_img");
        if (captchaImage) {
            captchaImage.src = g5_captcha_url + "/kcaptcha_image.php?t=" + new Date().getTime();
        }

        var audioUrl = syncCaptchaRequest(g5_captcha_url + "/kcaptcha_mp3.php");
        if (audioUrl) {
            mp3_url = audioUrl + "?t=" + new Date().getTime();
            var captchaAudio = document.getElementById("captcha_audio");
            if (captchaAudio) {
                captchaAudio.src = mp3_url;
            }
        }
    }

    reloadButton.addEventListener("click", function(event) {
        event.preventDefault();
        reloadCaptcha();
    });

    if (mp3Button && mp3Button.dataset.kcaptchaAudioBound !== "true") {
        mp3Button.dataset.kcaptchaAudioBound = "true";
        mp3Button.style.cursor = "pointer";
        mp3Button.addEventListener("click", function(event) {
            event.preventDefault();
            document.body.style.cursor = "wait";

            var audioUrl = syncCaptchaRequest(g5_captcha_url + "/kcaptcha_mp3.php");
            if (audioUrl) {
                mp3_url = audioUrl + "?t=" + new Date().getTime();
            }

            var html5use = false;
            var html5audio = document.createElement("audio");
            if (html5audio.canPlayType && html5audio.canPlayType("audio/mpeg")) {
                var wav = new Audio(mp3_url);
                wav.id = "mp3_audio";
                wav.autoplay = true;
                wav.controls = false;
                wav.autobuffer = false;
                wav.loop = false;

                var oldAudio = document.getElementById("mp3_audio");
                if (oldAudio) {
                    oldAudio.remove();
                }
                mp3Button.insertAdjacentElement("afterend", wav);

                html5use = true;
            }

            if (!html5use) {
                var oldObject = document.getElementById("mp3_object");
                if (oldObject) {
                    oldObject.remove();
                }

                var object = '<object id="mp3_object" classid="clsid:22d6f312-b0f6-11d0-94ab-0080c74c7e95" height="0" width="0" style="width:0; height:0;">';
                object += '<param name="AutoStart" value="1" />';
                object += '<param name="Volume" value="0" />';
                object += '<param name="PlayCount" value="1" />';
                object += '<param name="FileName" value="' + mp3_url + '" />';
                object += '<embed id="mp3_embed" src="' + mp3_url + '" autoplay="true" hidden="true" volume="100" type="audio/x-wav" style="display:inline;" />';
                object += "</object>";
                mp3Button.insertAdjacentHTML("afterend", object);
            }

            document.body.style.cursor = "default";
        });
    }

    reloadCaptcha();
}

if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", initKcaptcha);
} else {
    initKcaptcha();
}

// 출력된 캡챠이미지의 키값과 입력한 키값이 같은지 비교한다.
function chk_captcha()
{
    var captcha_result = false;
    var captcha_key = document.getElementById("captcha_key");
    var response = syncCaptchaRequest(g5_captcha_url + "/kcaptcha_result.php", {
        captcha_key: captcha_key ? captcha_key.value : ""
    });

    captcha_result = response;

    if (!captcha_result) {
        alert("자동등록방지 입력 글자가 틀렸거나 입력 횟수가 넘었습니다.\n\n새로고침을 클릭하여 다시 입력해 주십시오.");
        if (captcha_key) {
            captcha_key.select();
            captcha_key.focus();
        }
        return false;
    }

    return true;
}

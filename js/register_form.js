var g5_register_url = typeof g5_member_url !== "undefined" && g5_member_url ? g5_member_url : g5_url;

function normalizeRegisterCheckMessage(data) {
    return String(data == null ? "" : data).replace(/^\uFEFF/, "").trim();
}

function postRegisterCheck(path, data) {
    var xhr = new XMLHttpRequest();
    xhr.open("POST", g5_register_url + path, false);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded; charset=UTF-8");
    xhr.send(new URLSearchParams(data).toString());

    if (xhr.status >= 200 && xhr.status < 400) {
        return normalizeRegisterCheckMessage(xhr.responseText);
    }

    return "";
}

var reg_mb_id_check = function() {
    var field = document.getElementById("reg_mb_id");
    return postRegisterCheck("/ajax.mb_id.php", {
        reg_mb_id: encodeURIComponent(field ? field.value : "")
    });
}


// 추천인 검사
var reg_mb_recommend_check = function() {
    var field = document.getElementById("reg_mb_recommend");
    return postRegisterCheck("/ajax.mb_recommend.php", {
        reg_mb_recommend: encodeURIComponent(field ? field.value : "")
    });
}


var reg_mb_nick_check = function() {
    var nickField = document.getElementById("reg_mb_nick");
    var idField = document.getElementById("reg_mb_id");
    return postRegisterCheck("/ajax.mb_nick.php", {
        reg_mb_nick: nickField ? nickField.value : "",
        reg_mb_id: encodeURIComponent(idField ? idField.value : "")
    });
}


var reg_mb_email_check = function() {
    var emailField = document.getElementById("reg_mb_email");
    var idField = document.getElementById("reg_mb_id");
    return postRegisterCheck("/ajax.mb_email.php", {
        reg_mb_email: emailField ? emailField.value : "",
        reg_mb_id: encodeURIComponent(idField ? idField.value : "")
    });
}


var reg_mb_hp_check = function() {
    var hpField = document.getElementById("reg_mb_hp");
    var idField = document.getElementById("reg_mb_id");
    return postRegisterCheck("/ajax.mb_hp.php", {
        reg_mb_hp: hpField ? hpField.value : "",
        reg_mb_id: encodeURIComponent(idField ? idField.value : "")
    });
}

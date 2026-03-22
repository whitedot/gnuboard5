var g5_register_url = typeof g5_member_url !== "undefined" && g5_member_url ? g5_member_url : g5_url;

function normalizeRegisterCheckMessage(data) {
    return String(data == null ? "" : data).replace(/^\uFEFF/, "").trim();
}

var reg_mb_id_check = function() {
    var result = "";
    $.ajax({
        type: "POST",
        url: g5_register_url+"/ajax.mb_id.php",
        data: {
            "reg_mb_id": encodeURIComponent($("#reg_mb_id").val())
        },
        cache: false,
        async: false,
        success: function(data) {
            result = normalizeRegisterCheckMessage(data);
        }
    });
    return result;
}


// 추천인 검사
var reg_mb_recommend_check = function() {
    var result = "";
    $.ajax({
        type: "POST",
        url: g5_register_url+"/ajax.mb_recommend.php",
        data: {
            "reg_mb_recommend": encodeURIComponent($("#reg_mb_recommend").val())
        },
        cache: false,
        async: false,
        success: function(data) {
            result = normalizeRegisterCheckMessage(data);
        }
    });
    return result;
}


var reg_mb_nick_check = function() {
    var result = "";
    $.ajax({
        type: "POST",
        url: g5_register_url+"/ajax.mb_nick.php",
        data: {
            "reg_mb_nick": ($("#reg_mb_nick").val()),
            "reg_mb_id": encodeURIComponent($("#reg_mb_id").val())
        },
        cache: false,
        async: false,
        success: function(data) {
            result = normalizeRegisterCheckMessage(data);
        }
    });
    return result;
}


var reg_mb_email_check = function() {
    var result = "";
    $.ajax({
        type: "POST",
        url: g5_register_url+"/ajax.mb_email.php",
        data: {
            "reg_mb_email": $("#reg_mb_email").val(),
            "reg_mb_id": encodeURIComponent($("#reg_mb_id").val())
        },
        cache: false,
        async: false,
        success: function(data) {
            result = normalizeRegisterCheckMessage(data);
        }
    });
    return result;
}


var reg_mb_hp_check = function() {
    var result = "";
    $.ajax({
        type: "POST",
        url: g5_register_url+"/ajax.mb_hp.php",
        data: {
            "reg_mb_hp": $("#reg_mb_hp").val(),
            "reg_mb_id": encodeURIComponent($("#reg_mb_id").val())
        },
        cache: false,
        async: false,
        success: function(data) {
            result = normalizeRegisterCheckMessage(data);
        }
    });
    return result;
}

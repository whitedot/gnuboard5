// jQuery媛 ?녿뒗 ?섍꼍?먯꽌 ?ㅻ쪟 諛⑹?
if (typeof jQuery === 'undefined') {
    var $ = function(selector) {
        var noop = function() { return this; };
        return {
            ready: noop, click: noop, on: noop, off: noop,
            mouseover: noop, mouseout: noop, hover: noop,
            focusin: noop, focusout: noop,
            addClass: noop, removeClass: noop, toggleClass: noop,
            attr: noop, val: noop, find: noop, closest: noop, siblings: noop,
            prepend: noop, append: noop
        };
    };
    $.fn = {};
}

// ?꾩뿭 蹂??
var errmsg = "";
var errfld = null;

// ?꾨뱶 寃??
function check_field(fld, msg) {
    if ((fld.value = trim(fld.value)) == "")
        error_field(fld, msg);
    else
        clear_field(fld);
    return;
}

// ?꾨뱶 ?ㅻ쪟 ?쒖떆
function error_field(fld, msg) {
    if (msg != "")
        errmsg += msg + "\n";
    if (!errfld) errfld = fld;
    fld.style.background = "#BDDEF7";
}

// ?꾨뱶瑜?源⑤걮?섍쾶
function clear_field(fld) {
    fld.style.background = "#FFFFFF";
}

function trim(s) {
    var t = "";
    var from_pos = to_pos = 0;

    for (i = 0; i < s.length; i++) {
        if (s.charAt(i) == ' ')
            continue;
        else {
            from_pos = i;
            break;
        }
    }

    for (i = s.length; i >= 0; i--) {
        if (s.charAt(i - 1) == ' ')
            continue;
        else {
            to_pos = i;
            break;
        }
    }

    t = s.substring(from_pos, to_pos);
    //				alert(from_pos + ',' + to_pos + ',' + t+'.');
    return t;
}

// ?먮컮?ㅽ겕由쏀듃濡?PHP??number_format ?됰궡瑜???
// ?レ옄??, 瑜?異쒕젰
function number_format(data) {

    var tmp = '';
    var number = '';
    var cutlen = 3;
    var comma = ',';
    var i;

    data = data + '';

    var sign = data.match(/^[\+\-]/);
    if (sign) {
        data = data.replace(/^[\+\-]/, "");
    }

    len = data.length;
    mod = (len % cutlen);
    k = cutlen - mod;
    for (i = 0; i < data.length; i++) {
        number = number + data.charAt(i);

        if (i < data.length - 1) {
            k++;
            if ((k % cutlen) == 0) {
                number = number + comma;
                k = 0;
            }
        }
    }

    if (sign != null)
        number = sign + number;

    return number;
}

// ??李?
function popup_window(url, winname, opt) {
    window.open(url, winname, opt);
}


// ?쇰찓??李?
function popup_formmail(url) {
    opt = 'scrollbars=yes,width=417,height=385,top=10,left=20';
    popup_window(url, "wformmail", opt);
}

// , 瑜??놁븻??
function no_comma(data) {
    var tmp = '';
    var comma = ',';
    var i;

    for (i = 0; i < data.length; i++) {
        if (data.charAt(i) != comma)
            tmp += data.charAt(i);
    }
    return tmp;
}

// ??젣 寃???뺤씤
function del(href) {
    if (confirm("?쒕쾲 ??젣???먮즺??蹂듦뎄??諛⑸쾿???놁뒿?덈떎.\n\n?뺣쭚 ??젣?섏떆寃좎뒿?덇퉴?")) {
        window.location.href = href;
    }
}

// 荑좏궎 ?낅젰
function set_cookie(name, value, expirehours, domain) {
    var today = new Date();
    today.setTime(today.getTime() + (60 * 60 * 1000 * expirehours));
    document.cookie = name + "=" + escape(value) + "; path=/; expires=" + today.toGMTString() + ";";
    if (domain) {
        document.cookie += "domain=" + domain + ";";
    }
}

// 荑좏궎 ?살쓬
function get_cookie(name) {
    var match = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'));
    if (match) return unescape(match[2]);
    return "";
}

// 荑좏궎 吏?
function delete_cookie(name) {
    var today = new Date();

    today.setTime(today.getTime() - 1);
    var value = get_cookie(name);
    if (value != "")
        document.cookie = name + "=" + value + "; path=/; expires=" + today.toGMTString();
}

var last_id = null;
function menu(id) {
    if (id != last_id) {
        if (last_id != null)
            document.getElementById(last_id).style.display = "none";
        document.getElementById(id).style.display = "block";
        last_id = id;
    }
    else {
        document.getElementById(id).style.display = "none";
        last_id = null;
    }
}

function textarea_decrease(id, row) {
    if (document.getElementById(id).rows - row > 0)
        document.getElementById(id).rows -= row;
}

function textarea_original(id, row) {
    document.getElementById(id).rows = row;
}

function textarea_increase(id, row) {
    document.getElementById(id).rows += row;
}

// 湲?レ옄 寃??
function check_byte(content, target) {
    var i = 0;
    var cnt = 0;
    var ch = '';
    var cont = document.getElementById(content).value;

    for (i = 0; i < cont.length; i++) {
        ch = cont.charAt(i);
        if (escape(ch).length > 4) {
            cnt += 2;
        } else {
            cnt += 1;
        }
    }
    // ?レ옄瑜?異쒕젰
    document.getElementById(target).innerHTML = cnt;

    return cnt;
}

// 釉뚮씪?곗??먯꽌 ?ㅻ툕?앺듃???쇱そ 醫뚰몴
function get_left_pos(obj) {
    var parentObj = null;
    var clientObj = obj;
    //var left = obj.offsetLeft + document.body.clientLeft;
    var left = obj.offsetLeft;

    while ((parentObj = clientObj.offsetParent) != null) {
        left = left + parentObj.offsetLeft;
        clientObj = parentObj;
    }

    return left;
}

// 釉뚮씪?곗??먯꽌 ?ㅻ툕?앺듃???곷떒 醫뚰몴
function get_top_pos(obj) {
    var parentObj = null;
    var clientObj = obj;
    //var top = obj.offsetTop + document.body.clientTop;
    var top = obj.offsetTop;

    while ((parentObj = clientObj.offsetParent) != null) {
        top = top + parentObj.offsetTop;
        clientObj = parentObj;
    }

    return top;
}

function flash_movie(src, ids, width, height, wmode) {
    var wh = "";
    if (parseInt(width) && parseInt(height))
        wh = " width='" + width + "' height='" + height + "' ";
    return "<object classid='clsid:d27cdb6e-ae6d-11cf-96b8-444553540000' codebase='http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0' " + wh + " id=" + ids + "><param name=wmode value=" + wmode + "><param name=movie value=" + src + "><param name=quality value=high><embed src=" + src + " quality=high wmode=" + wmode + " type='application/x-shockwave-flash' pluginspage='http://www.macromedia.com/shockwave/download/index.cgi?p1_prod_version=shockwaveflash' " + wh + "></embed></object>";
}

function obj_movie(src, ids, width, height, autostart) {
    var wh = "";
    if (parseInt(width) && parseInt(height))
        wh = " width='" + width + "' height='" + height + "' ";
    if (!autostart) autostart = false;
    return "<embed src='" + src + "' " + wh + " autostart='" + autostart + "'></embed>";
}

function doc_write(cont) {
    document.write(cont);
}

var win_password_lost = function (href) {
    window.open(href, "win_password_lost", "left=50, top=50, width=617, height=330, scrollbars=1");
}

document.addEventListener('DOMContentLoaded', function () {
    var passwordLostLinks = document.querySelectorAll('#login_password_lost, #ol_password_lost');
    passwordLostLinks.forEach(function (link) {
        link.addEventListener('click', function (e) {
            e.preventDefault();
            win_password_lost(this.href);
        });
    });
});

/**
 * ?ъ씤??李?
 **/
var win_point = function (href) {
    var new_win = window.open(href, 'win_point', 'left=100,top=100,width=600, height=600, scrollbars=1');
    new_win.focus();
}

/**
 * 履쎌? 李?
 **/
var win_memo = function (href) {
    var new_win = window.open(href, 'win_memo', 'left=100,top=100,width=620,height=500,scrollbars=1');
    new_win.focus();
}

/**
 * 履쎌? 李?
 **/
var check_goto_new = function (href, event) {
    if (!(typeof g5_is_mobile != "undefined" && g5_is_mobile)) {
        if (window.opener && window.opener.document && window.opener.document.getElementById) {
            event.preventDefault ? event.preventDefault() : (event.returnValue = false);
            window.open(href);
            //window.opener.document.location.href = href;
        }
    }
}

/**
 * 硫붿씪 李?
 **/
var win_email = function (href) {
    var new_win = window.open(href, 'win_email', 'left=100,top=100,width=600,height=580,scrollbars=1');
    new_win.focus();
}

/**
 * ?먭린?뚭컻 李?
 **/
var win_profile = function (href) {
    var new_win = window.open(href, 'win_profile', 'left=100,top=100,width=620,height=510,scrollbars=1');
    new_win.focus();
}

/**
 * ?ㅽ겕??李?
 **/
var win_scrap = function (href) {
    var new_win = window.open(href, 'win_scrap', 'left=100,top=100,width=600,height=600,scrollbars=1');
    new_win.focus();
}

/**
 * ?덊럹?댁? 李?
 **/
var win_homepage = function (href) {
    var new_win = window.open(href, 'win_homepage', '');
    new_win.focus();
}

/**
 * ?고렪踰덊샇 李?
 **/
var win_zip = function (frm_name, frm_zip, frm_addr1, frm_addr2, frm_addr3, frm_jibeon) {
    if (typeof kakao === "undefined") {
        alert("KAKAO ?고렪踰덊샇 ?쒕퉬??postcode.v2.js ?뚯씪??濡쒕뱶?섏? ?딆븯?듬땲??");
        return false;
    }

    // ?移?以??꾩긽 ?쒓굅
    var vContent = "width=device-width,initial-scale=1.0,minimum-scale=0,maximum-scale=10";
    $("#meta_viewport").attr("content", vContent + ",user-scalable=no");

    var zip_case = 1;   //0?대㈃ ?덉씠?? 1?대㈃ ?섏씠吏???쇱썙 ?ｊ린, 2?대㈃ ?덉갹

    var complete_fn = function (data) {
        // ?앹뾽?먯꽌 寃?됯껐怨???ぉ???대┃?덉쓣???ㅽ뻾??肄붾뱶瑜??묒꽦?섎뒗 遺遺?

        // 媛?二쇱냼???몄텧 洹쒖튃???곕씪 二쇱냼瑜?議고빀?쒕떎.
        // ?대젮?ㅻ뒗 蹂?섍? 媛믪씠 ?녿뒗 寃쎌슦??怨듬갚('')媛믪쓣 媛吏誘濡? ?대? 李멸퀬?섏뿬 遺꾧린 ?쒕떎.
        var fullAddr = ''; // 理쒖쥌 二쇱냼 蹂??
        var extraAddr = ''; // 議고빀??二쇱냼 蹂??

        // ?ъ슜?먭? ?좏깮??二쇱냼 ??낆뿉 ?곕씪 ?대떦 二쇱냼 媛믪쓣 媛?몄삩??
        if (data.userSelectedType === 'R') { // ?ъ슜?먭? ?꾨줈紐?二쇱냼瑜??좏깮?덉쓣 寃쎌슦
            fullAddr = data.roadAddress;

        } else { // ?ъ슜?먭? 吏踰?二쇱냼瑜??좏깮?덉쓣 寃쎌슦(J)
            fullAddr = data.jibunAddress;
        }

        // ?ъ슜?먭? ?좏깮??二쇱냼媛 ?꾨줈紐???낆씪??議고빀?쒕떎.
        if (data.userSelectedType === 'R') {
            //踰뺤젙?숇챸???덉쓣 寃쎌슦 異붽??쒕떎.
            if (data.bname !== '') {
                extraAddr += data.bname;
            }
            // 嫄대Ъ紐낆씠 ?덉쓣 寃쎌슦 異붽??쒕떎.
            if (data.buildingName !== '') {
                extraAddr += (extraAddr !== '' ? ', ' + data.buildingName : data.buildingName);
            }
            // 議고빀?뺤＜?뚯쓽 ?좊Т???곕씪 ?묒そ??愿꾪샇瑜?異붽??섏뿬 理쒖쥌 二쇱냼瑜?留뚮뱺??
            extraAddr = (extraAddr !== '' ? ' (' + extraAddr + ')' : '');
        }

        // ?고렪踰덊샇? 二쇱냼 ?뺣낫瑜??대떦 ?꾨뱶???ｊ퀬, 而ㅼ꽌瑜??곸꽭二쇱냼 ?꾨뱶濡??대룞?쒕떎.
        var of = document[frm_name];

        of[frm_zip].value = data.zonecode;

        of[frm_addr1].value = fullAddr;
        of[frm_addr3].value = extraAddr;

        if (of[frm_jibeon] !== undefined) {
            of[frm_jibeon].value = data.userSelectedType;
        }

        setTimeout(function () {
            $("#meta_viewport").attr("content", vContent);
            of[frm_addr2].focus();
        }, 100);
    };

    switch (zip_case) {
        case 1:    //iframe???댁슜?섏뿬 ?섏씠吏???쇱썙 ?ｊ린
            var kakao_pape_id = 'kakao_juso_page' + frm_zip,
                element_wrap = document.getElementById(kakao_pape_id),
                currentScroll = Math.max(document.body.scrollTop, document.documentElement.scrollTop);
            if (element_wrap == null) {
                element_wrap = document.createElement("div");
                element_wrap.setAttribute("id", kakao_pape_id);
                element_wrap.style.cssText = 'display:none;border:1px solid;left:0;width:100%;height:300px;margin:5px 0;position:relative;-webkit-overflow-scrolling:touch;';
                element_wrap.innerHTML = '<img src="//t1.kakaocdn.net/postcode/resource/images/close.png" id="btnFoldWrap" style="cursor:pointer;position:absolute;right:0px;top:-21px;z-index:1" class="close_kakao_juso" alt="?묎린 踰꾪듉">';
                jQuery('form[name="' + frm_name + '"]').find('input[name="' + frm_addr1 + '"]').before(element_wrap);
                jQuery("#" + kakao_pape_id).off("click", ".close_kakao_juso").on("click", ".close_kakao_juso", function (e) {
                    e.preventDefault();
                    $("#meta_viewport").attr("content", vContent);
                    jQuery(this).parent().hide();
                });
            }

            new kakao.Postcode({
                oncomplete: function (data) {
                    complete_fn(data);
                    // iframe???ｌ? element瑜??덈낫?닿쾶 ?쒕떎.
                    element_wrap.style.display = 'none';
                    // ?고렪踰덊샇 李얘린 ?붾㈃??蹂댁씠湲??댁쟾?쇰줈 scroll ?꾩튂瑜??섎룎由곕떎.
                    document.body.scrollTop = currentScroll;
                },
                // ?고렪踰덊샇 李얘린 ?붾㈃ ?ш린媛 議곗젙?섏뿀?꾨븣 ?ㅽ뻾??肄붾뱶瑜??묒꽦?섎뒗 遺遺?
                // iframe???ｌ? element???믪씠媛믪쓣 議곗젙?쒕떎.
                onresize: function (size) {
                    element_wrap.style.height = size.height + "px";
                },
                maxSuggestItems: g5_is_mobile ? 6 : 10,
                width: '100%',
                height: '100%'
            }).embed(element_wrap);

            // iframe???ｌ? element瑜?蹂댁씠寃??쒕떎.
            element_wrap.style.display = 'block';
            break;
        case 2:    //?덉갹?쇰줈 ?꾩슦湲?
            new kakao.Postcode({
                oncomplete: function (data) {
                    complete_fn(data);
                }
            }).open();
            break;
        default:   //iframe???댁슜?섏뿬 ?덉씠???꾩슦湲?
            var rayer_id = 'kakao_juso_rayer' + frm_zip,
                element_layer = document.getElementById(rayer_id);
            if (element_layer == null) {
                element_layer = document.createElement("div");
                element_layer.setAttribute("id", rayer_id);
                element_layer.style.cssText = 'display:none;border:5px solid;position:fixed;width:300px;height:460px;left:50%;margin-left:-155px;top:50%;margin-top:-235px;overflow:hidden;-webkit-overflow-scrolling:touch;z-index:10000';
                element_layer.innerHTML = '<img src="//i1.kakaocdn.net/localimg/localimages/07/postcode/320/close.png" id="btnCloseLayer" style="cursor:pointer;position:absolute;right:-3px;top:-3px;z-index:1" class="close_kakao_juso" alt="?リ린 踰꾪듉">';
                document.body.appendChild(element_layer);
                jQuery("#" + rayer_id).off("click", ".close_kakao_juso").on("click", ".close_kakao_juso", function (e) {
                    e.preventDefault();
                    $("#meta_viewport").attr("content", vContent);
                    jQuery(this).parent().hide();
                });
            }

            new kakao.Postcode({
                oncomplete: function (data) {
                    complete_fn(data);
                    // iframe???ｌ? element瑜??덈낫?닿쾶 ?쒕떎.
                    element_layer.style.display = 'none';
                },
                maxSuggestItems: g5_is_mobile ? 6 : 10,
                width: '100%',
                height: '100%'
            }).embed(element_layer);

            // iframe???ｌ? element瑜?蹂댁씠寃??쒕떎.
            element_layer.style.display = 'block';
    }
}

/**
 * ?덈줈??鍮꾨?踰덊샇 遺꾩떎 李?: 101123
 **/
win_password_lost = function (href) {
    var new_win = window.open(href, 'win_password_lost', 'width=617, height=330, scrollbars=1');
    new_win.focus();
}

/**
 * ?ㅻЦ議곗궗 寃곌낵
 **/
var win_poll = function (href) {
    var new_win = window.open(href, 'win_poll', 'width=616, height=500, scrollbars=1');
    new_win.focus();
}

/**
 * 荑좏룿
 **/
var win_coupon = function (href) {
    var new_win = window.open(href, "win_coupon", "left=100,top=100,width=700, height=600, scrollbars=1");
    new_win.focus();
}


/**
 * ?ㅽ겕由곕━??誘몄궗?⑹옄瑜??꾪븳 ?ㅽ겕由쏀듃 - 吏?댁븘鍮?2013-04-22
 * alt 媛믩쭔 媛뽯뒗 洹몃옒??留곹겕??留덉슦?ㅼ삤踰???title 媛?遺?? 留덉슦?ㅼ븘????title 媛??쒓굅
 **/
$(function () {
    $('a img').mouseover(function () {
        $a_img_title = $(this).attr('alt');
        $(this).attr('title', $a_img_title);
    }).mouseout(function () {
        $(this).attr('title', '');
    });
});

/**
 * ?띿뒪??由ъ궗?댁쫰
**/
function font_resize(id, rmv_class, add_class, othis) {
    var $el = $("#" + id);

    if ((typeof rmv_class !== "undefined" && rmv_class) || (typeof add_class !== "undefined" && add_class)) {
        $el.removeClass(rmv_class).addClass(add_class);

        set_cookie("ck_font_resize_rmv_class", rmv_class, 1, g5_cookie_domain);
        set_cookie("ck_font_resize_add_class", add_class, 1, g5_cookie_domain);
    }

    if (typeof othis !== "undefined") {
        $(othis).addClass('select').siblings().removeClass('select');
    }
}

/**
 * ?볤? ?섏젙 ?좏겙
**/
function set_comment_token(f) {
    if (typeof f.token === "undefined")
        $(f).prepend('<input type="hidden" name="token" value="">');

    $.ajax({
        url: g5_bbs_url + "/ajax.comment_token.php",
        type: "GET",
        dataType: "json",
        async: false,
        cache: false,
        success: function (data, textStatus) {
            f.token.value = data.token;
        }
    });
}

$(function () {
    $(".win_point").click(function () {
        win_point(this.href);
        return false;
    });

    $(".win_memo").click(function () {
        win_memo(this.href);
        return false;
    });

    $(".win_email").click(function () {
        win_email(this.href);
        return false;
    });

    $(".win_scrap").click(function () {
        win_scrap(this.href);
        return false;
    });

    $(".win_profile").click(function () {
        win_profile(this.href);
        return false;
    });

    $(".win_homepage").click(function () {
        win_homepage(this.href);
        return false;
    });

    $(".win_password_lost").click(function () {
        win_password_lost(this.href);
        return false;
    });

    /*
    $(".win_poll").click(function() {
        win_poll(this.href);
        return false;
    });
    */

    $(".win_coupon").click(function () {
        win_coupon(this.href);
        return false;
    });

    // 사이드뷰(레거시: hs-dropdown 미적용 마크업만 처리)
    var sv_hide = false;
    var legacy_sv_wrap = ".sv_wrap:not(.hs-dropdown)";
    var legacy_sv_menu = ".sv_wrap:not(.hs-dropdown) .sv";
    var legacy_sv_trigger = ".sv_wrap:not(.hs-dropdown) .sv_member, .sv_wrap:not(.hs-dropdown) .sv_guest";

    $(legacy_sv_trigger).click(function () {
        $(legacy_sv_menu).removeClass("sv_on");
        $(this).closest(".sv_wrap").find(".sv").addClass("sv_on");
    });

    $(legacy_sv_menu + ", " + legacy_sv_wrap).hover(
        function () {
            sv_hide = false;
        },
        function () {
            sv_hide = true;
        }
    );

    $(legacy_sv_trigger).focusin(function () {
        sv_hide = false;
        $(legacy_sv_menu).removeClass("sv_on");
        $(this).closest(".sv_wrap").find(".sv").addClass("sv_on");
    });

    $(legacy_sv_menu + " a").focusin(function () {
        sv_hide = false;
    });

    $(legacy_sv_menu + " a").focusout(function () {
        sv_hide = true;
    });
    // ??됲듃 ul
    var sel_hide = false;
    $('.sel_btn').click(function () {
        $('.sel_ul').removeClass('sel_on');
        $(this).siblings('.sel_ul').addClass('sel_on');
    });

    $(".sel_wrap").hover(
        function () {
            sel_hide = false;
        },
        function () {
            sel_hide = true;
        }
    );

    $('.sel_a').focusin(function () {
        sel_hide = false;
    });

    $('.sel_a').focusout(function () {
        sel_hide = true;
    });

    $(document).click(function () {
        if (sv_hide) { // ?ъ씠?쒕럭 ?댁젣
            $(legacy_sv_menu).removeClass("sv_on");
        }
        if (sel_hide) { // ??됲듃 ul ?댁젣
            $('.sel_ul').removeClass('sel_on');
        }
    });

    $(document).focusin(function () {
        if (sv_hide) { // ?ъ씠?쒕럭 ?댁젣
            $(legacy_sv_menu).removeClass("sv_on");
        }
        if (sel_hide) { // ??됲듃 ul ?댁젣
            $('.sel_ul').removeClass('sel_on');
        }
    });

    $(document).on("keyup change", "textarea#wr_content[maxlength]", function () {
        var str = $(this).val();
        var mx = parseInt($(this).attr("maxlength"));
        if (str.length > mx) {
            $(this).val(str.substr(0, mx));
            return false;
        }
    });
});

function get_write_token(bo_table) {
    var token = "";

    $.ajax({
        type: "POST",
        url: g5_bbs_url + "/write_token.php",
        data: { bo_table: bo_table },
        cache: false,
        async: false,
        dataType: "json",
        success: function (data) {
            if (data.error) {
                alert(data.error);
                if (data.url)
                    document.location.href = data.url;

                return false;
            }

            token = data.token;
        }
    });

    return token;
}

$(function () {
    $(document).on("click", "form[name=fwrite] input:submit, form[name=fwrite] button:submit, form[name=fwrite] input:image", function () {
        var f = this.form;

        if (typeof (f.bo_table) == "undefined") {
            return;
        }

        var bo_table = f.bo_table.value;
        var token = get_write_token(bo_table);

        if (!token) {
            alert("?좏겙 ?뺣낫媛 ?щ컮瑜댁? ?딆뒿?덈떎.");
            return false;
        }

        var $f = $(f);

        if (typeof f.token === "undefined")
            $f.prepend('<input type="hidden" name="token" value="">');

        $f.find("input[name=token]").val(token);

        return true;
    });
});


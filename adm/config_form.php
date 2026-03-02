<?php
$sub_menu = "100100";
require_once './_common.php';

auth_check_menu($auth, $sub_menu, 'r');

if ($is_admin != 'super') {
    alert('최고관리자만 접근 가능합니다.');
}

// https://github.com/gnuboard/gnuboard5/issues/296 대응
$sql = " select * from {$g5['config_table']} limit 1";
$config = sql_fetch($sql);

if (!$config['cf_faq_skin']) {
    $config['cf_faq_skin'] = 'basic';
}

$g5['title'] = '환경설정';
require_once './admin.head.php';
?>
<style>
    .hint-text {
        margin-bottom: var(--spacing-base);
        border-radius: var(--radius);
        border: 1px dashed var(--color-default-300);
        background-color: var(--color-default-100);
        padding: calc(var(--spacing) * 4);
        font-size: var(--text-sm);
        color: var(--color-default-600);
        line-height: 1.5;
    }

    .hint-text p {
        margin: 0;
    }

    /* 환경설정 탭을 상단 일렬 배치 */
    #fconfigform .config-shell {
        display: block;
    }

    #fconfigform .config-sidebar {
        position: sticky;
        top: calc(var(--admin-shell-bar-height) + 1rem);
        z-index: 20;
        margin-bottom: calc(var(--spacing) * 4);
    }

    #fconfigform .config-submenu .tab-nav {
        overflow-x: auto;
        white-space: nowrap;
        scrollbar-width: thin;
    }

    #fconfigform .section-anchor {
        display: inline-flex;
        align-items: center;
        gap: calc(var(--spacing) * 1);
    }

    #fconfigform .section-anchor li {
        margin: 0;
    }

    #fconfigform .section-anchor .tab-trigger-underline {
        text-decoration: none;
        color: var(--color-default-900);
        border-bottom-color: transparent;
        opacity: 1;
    }

    #fconfigform .section-anchor .tab-trigger-underline.active {
        color: var(--color-primary);
        border-bottom-color: var(--color-primary);
    }

    [data-theme='dark'] #fconfigform .section-anchor .tab-trigger-underline {
        color: var(--color-default-800);
        background-color: var(--color-default-100);
        border: 1px solid var(--color-default-300);
        border-bottom-color: transparent;
        opacity: 1;
    }

    [data-theme='dark'] #fconfigform .section-anchor .tab-trigger-underline.active {
        color: var(--color-default-900);
        border-bottom-color: var(--color-primary);
    }

    #fconfigform .config-content > section,
    #fconfigform .config-content > #anc_cf_url,
    #fconfigform .config-content > #config_captcha_wrap {
        scroll-margin-top: calc(var(--admin-shell-bar-height) + 11rem);
    }
</style>
<?php

$pg_anchor = '';
$pg_anchor_menu = '
<nav class="config-submenu tab-nav" aria-label="환경설정 탭">
    <ul class="section-anchor">
        <li><a href="#anc_cf_basic" class="config-anchor-link tab-trigger-underline active">기본</a></li>
        <li><a href="#anc_cf_board" class="config-anchor-link tab-trigger-underline">게시판</a></li>
        <li><a href="#anc_cf_join" class="config-anchor-link tab-trigger-underline">회원</a></li>
        <li><a href="#anc_cf_cert" class="config-anchor-link tab-trigger-underline">본인확인</a></li>
        <li><a href="#anc_cf_url" class="config-anchor-link tab-trigger-underline">URL</a></li>
        <li><a href="#anc_cf_mail" class="config-anchor-link tab-trigger-underline">메일</a></li>
        <li><a href="#anc_cf_article_mail" class="config-anchor-link tab-trigger-underline">글작성 메일</a></li>
        <li><a href="#anc_cf_join_mail" class="config-anchor-link tab-trigger-underline">가입 메일</a></li>
        <li><a href="#anc_cf_vote_mail" class="config-anchor-link tab-trigger-underline">투표 메일</a></li>
        <li><a href="#anc_cf_sns" class="config-anchor-link tab-trigger-underline">SNS</a></li>
        <li><a href="#anc_cf_lay" class="config-anchor-link tab-trigger-underline">레이아웃</a></li>
        <li><a href="#anc_cf_sms" class="config-anchor-link tab-trigger-underline">SMS</a></li>
    </ul>
</nav>';

if (!$config['cf_icode_server_ip']) {
    $config['cf_icode_server_ip'] = '211.172.232.124';
}
if (!$config['cf_icode_server_port']) {
    $config['cf_icode_server_port'] = '7295';
}

$userinfo = array('payment' => '');
if ($config['cf_sms_use'] && $config['cf_icode_id'] && $config['cf_icode_pw']) {
    $userinfo = get_icode_userinfo($config['cf_icode_id'], $config['cf_icode_pw']);
}
?>

<form name="fconfigform" id="fconfigform" method="post" onsubmit="return fconfigform_submit(this);">
    <input type="hidden" name="token" value="" id="token">

    <div class="config-shell">
        <aside class="config-sidebar">
            <?php echo $pg_anchor_menu; ?>
        </aside>

        <div class="config-content">
            <?php
            include_once G5_ADMIN_PATH.'/config_form_parts/basic.php';
            include_once G5_ADMIN_PATH.'/config_form_parts/board.php';
            include_once G5_ADMIN_PATH.'/config_form_parts/join.php';
            include_once G5_ADMIN_PATH.'/config_form_parts/cert.php';
            require_once '_rewrite_config_form.php';
            include_once G5_ADMIN_PATH.'/config_form_parts/mail.php';
            include_once G5_ADMIN_PATH.'/config_form_parts/sns.php';
            include_once G5_ADMIN_PATH.'/config_form_parts/layout.php';
            include_once G5_ADMIN_PATH.'/config_form_parts/sms.php';
            ?>

            <div id="config_captcha_wrap" class="hidden">
                
                    <h2>캡차 입력</h2>
                
                
                    <?php
                    require_once G5_CAPTCHA_PATH . '/captcha.lib.php';
                    $captcha_html = captcha_html();
                    $captcha_js   = chk_captcha_js();
                    echo $captcha_html;
                    ?>
                
            </div>
        </div>
    </div>

    
        <input type="submit" value="저장" accesskey="s">
    
</form>

<script>
(function () {
    const root = document.getElementById('fconfigform');
    if (!root) return;

    root.querySelectorAll('input').forEach((el) => {
        const type = (el.getAttribute('type') || 'text').toLowerCase();
        if (['hidden', 'checkbox', 'radio', 'file', 'submit', 'button', 'reset'].includes(type)) return;
        el.classList.add('form-input');
    });

    root.querySelectorAll('select').forEach((el) => {
        el.classList.add('form-select');
    });

    root.querySelectorAll('textarea').forEach((el) => {
        el.classList.add('form-textarea');
    });
})();
</script>

<script>
(function () {
    const links = Array.from(document.querySelectorAll('#fconfigform .config-anchor-link'));
    if (!links.length) return;
    const sidebar = document.querySelector('#fconfigform .config-sidebar');

    const pairs = links
        .map((link) => {
            const id = link.getAttribute('href')?.replace('#', '');
            if (!id) return null;
            const section = document.getElementById(id);
            if (!section) return null;
            return { link, section };
        })
        .filter(Boolean);

    if (!pairs.length) return;

    const setActive = (activeLink) => {
        links.forEach((link) => {
            const isActive = link === activeLink;
            link.classList.toggle('active', isActive);
            if (isActive) link.setAttribute('aria-current', 'true');
            else link.removeAttribute('aria-current');
        });
    };

    const firstVisible = pairs[0].link;
    setActive(firstVisible);

    const getStickyOffset = () => {
        const shellBar = getComputedStyle(document.documentElement)
            .getPropertyValue('--admin-shell-bar-height')
            .trim();
        const shellBarPx = shellBar.endsWith('px') ? parseFloat(shellBar) : 0;
        const sidebarHeight = sidebar ? sidebar.getBoundingClientRect().height : 0;
        return shellBarPx + sidebarHeight + 16;
    };

    const updateActiveByScroll = () => {
        const offset = getStickyOffset();
        const scrollY = window.scrollY + offset;

        let current = pairs[0];
        for (const pair of pairs) {
            const top = pair.section.getBoundingClientRect().top + window.scrollY;
            if (top <= scrollY) current = pair;
            else break;
        }
        setActive(current.link);
    };

    let ticking = false;
    const requestUpdate = () => {
        if (ticking) return;
        ticking = true;
        requestAnimationFrame(() => {
            updateActiveByScroll();
            ticking = false;
        });
    };

    links.forEach((link) => {
        link.addEventListener('click', (event) => {
            const id = link.getAttribute('href')?.replace('#', '');
            if (!id) return;
            const section = document.getElementById(id);
            if (!section) return;

            event.preventDefault();
            const top = section.getBoundingClientRect().top + window.scrollY - getStickyOffset() + 8;
            window.scrollTo({ top, behavior: 'smooth' });
            history.replaceState(null, '', '#' + id);
            setActive(link);
        });
    });

    window.addEventListener('scroll', requestUpdate, { passive: true });
    window.addEventListener('resize', requestUpdate);
    window.addEventListener('hashchange', requestUpdate);
    requestUpdate();
})();
</script>

<?php
// 자바스크립트 및 기타 로직
include_once G5_ADMIN_PATH.'/config_form_parts/script.php';

require_once './admin.tail.php';
?>

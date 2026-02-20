<?php include 'layout/header.php'; ?>
<?php include 'layout/sidebar.php'; ?>

<style>
    .sidebar-demo-shell {
        border: 1px solid var(--color-default-300);
        border-radius: 1rem;
        background: var(--color-default-100);
        padding: 1rem;
        overflow-x: auto;
    }

    .admin-sidebar-kit {
        width: 19rem;
        min-height: 42rem;
        display: flex;
        flex-direction: column;
        border: 1px solid var(--color-default-300);
        border-radius: 1rem;
        background: var(--color-card);
        box-shadow: var(--shadow-sm);
        overflow: hidden;
    }

    .admin-sidebar-kit > h2 {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.75rem;
        border-bottom: 1px solid var(--color-default-300);
        padding: 1.25rem;
    }

    .admin-sidebar-kit > h2 > a {
        display: flex;
        min-width: 0;
        align-items: center;
        gap: 0.75rem;
    }

    .admin-sidebar-kit > h2 > a > span:first-child {
        display: inline-flex;
        height: 2.5rem;
        width: 2.5rem;
        align-items: center;
        justify-content: center;
        border-radius: 0.75rem;
        background: #0f172a;
        font-size: 1rem;
        font-weight: 600;
        color: #fff;
    }

    .admin-sidebar-kit > h2 > a > span:last-child {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        font-size: 1.375rem;
        font-weight: 600;
        letter-spacing: -0.015em;
        color: var(--color-default-900);
    }

    .admin-sidebar-kit .gnb_menu_scroll_wrap {
        position: relative;
        display: flex;
        min-height: 0;
        flex: 1;
    }

    .admin-sidebar-kit .gnb_menu_scroll {
        height: 100%;
        width: 100%;
        overflow-y: auto;
        padding: 1.25rem 1rem 1rem;
        scrollbar-width: none;
        -ms-overflow-style: none;
    }

    .admin-sidebar-kit .gnb_menu_scroll::-webkit-scrollbar {
        width: 0;
        height: 0;
    }

    .admin-sidebar-kit .gnb_scrollbar {
        position: absolute;
        top: 0.5rem;
        right: 0.25rem;
        bottom: 0.5rem;
        width: 0.5rem;
        border-radius: 9999px;
        opacity: 0;
        pointer-events: none;
        transition: opacity 140ms ease;
    }

    .admin-sidebar-kit .gnb_scrollbar_thumb {
        position: absolute;
        left: 0;
        width: 100%;
        height: 0;
        transform: translateY(0);
        border-radius: 9999px;
        background: color-mix(in oklab, var(--color-default-500) 72%, transparent);
        transition: background 140ms ease;
    }

    .admin-sidebar-kit .gnb_menu_scroll_wrap.is-scrollable.is-scrollbar-visible .gnb_scrollbar {
        opacity: 1;
    }

    .admin-sidebar-kit .gnb_menu_scroll_wrap.is-scrollable.is-scrollbar-visible .gnb_scrollbar_thumb {
        background: color-mix(in oklab, var(--color-default-500) 82%, transparent);
    }

    .admin-sidebar-kit .gnb_label {
        margin-bottom: 0.75rem;
        padding-inline: 0.5rem;
        font-size: 0.75rem;
        font-weight: 600;
        letter-spacing: 0.04em;
        color: var(--color-default-400);
    }

    .admin-sidebar-kit .admin-nav-list {
        display: grid;
        gap: 0.375rem;
    }

    .admin-sidebar-kit .admin-nav-item > .admin-nav-trigger {
        display: flex;
        width: 100%;
        align-items: center;
        justify-content: space-between;
        border-radius: 0.5rem;
        padding: 0.5rem 0.625rem;
        text-align: left;
        font-size: 0.875rem;
        font-weight: 500;
        color: var(--color-default-700);
        transition: color 0.15s ease;
    }

    .admin-sidebar-kit .admin-nav-item > .admin-nav-trigger:hover {
        color: var(--color-default-900);
    }

    .admin-sidebar-kit .admin-nav-item.is-open > .admin-nav-trigger {
        color: var(--color-default-900);
    }

    .admin-sidebar-kit .admin-nav-item.is-open > .admin-nav-trigger .admin-nav-trigger-label {
        font-weight: 600;
    }

    .admin-sidebar-kit .admin-nav-caret {
        margin-left: 0.75rem;
        font-size: 0.75rem;
        font-weight: 600;
        color: var(--color-default-500);
        transition: transform 150ms ease;
    }

    .admin-sidebar-kit .admin-nav-item.is-open .admin-nav-caret {
        transform: rotate(180deg);
    }

    .admin-sidebar-kit .admin-nav-panel {
        margin-left: 0.5rem;
        padding-left: 1.25rem;
        padding-bottom: 0.25rem;
    }

    .admin-sidebar-kit .admin-nav-sub-list {
        display: grid;
        gap: 0.25rem;
        padding-block: 0.25rem;
    }

    .admin-sidebar-kit .admin-nav-sub-item {
        position: relative;
    }

    .admin-sidebar-kit .admin-nav-sub-item::before {
        content: "";
        position: absolute;
        left: -0.85rem;
        top: calc(-0.45rem - 8px);
        bottom: calc(-0.45rem + 8px);
        width: 1px;
        background: color-mix(in oklab, var(--color-default-500) 45%, transparent);
        pointer-events: none;
    }

    .admin-sidebar-kit .admin-nav-sub-item:first-child::before {
        top: calc(0.8rem - 8px);
    }

    .admin-sidebar-kit .admin-nav-sub-item:last-child::before {
        top: calc(-0.45rem - 8px);
        bottom: auto;
        height: 1.25rem;
    }

    .admin-sidebar-kit .admin-nav-sub-item:only-child::before {
        display: none;
    }

    .admin-sidebar-kit .admin-nav-sub-item::after {
        content: "";
        position: absolute;
        left: -0.85rem;
        top: calc(0.8rem - 8px);
        width: 0.8rem;
        height: 0.8rem;
        border-left: 1px solid color-mix(in oklab, var(--color-default-500) 45%, transparent);
        border-bottom: 1px solid color-mix(in oklab, var(--color-default-500) 45%, transparent);
        border-bottom-left-radius: 0.75rem;
        pointer-events: none;
    }

    .admin-sidebar-kit .admin-nav-sub-item > a {
        display: block;
        border-radius: 0.375rem;
        padding: 0.375rem 0.5rem;
        font-size: 0.875rem;
        color: var(--color-default-600);
        transition: color 0.15s ease;
    }

    .admin-sidebar-kit .admin-nav-sub-item > a:hover {
        color: var(--color-default-900);
    }

    .admin-sidebar-kit .admin-nav-sub-item.is-current > a {
        color: var(--color-primary);
        font-weight: 600;
    }

    .admin-sidebar-kit .gnb_profile {
        margin-top: auto;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        border-top: 1px solid var(--color-default-300);
        padding: 1rem;
    }

    .admin-sidebar-kit .gnb_profile_avatar {
        display: inline-flex;
        height: 2.75rem;
        width: 2.75rem;
        flex-shrink: 0;
        align-items: center;
        justify-content: center;
        border-radius: 0.75rem;
        background: #0f172a;
        font-size: 0.875rem;
        font-weight: 600;
        color: #fff;
    }

    .admin-sidebar-kit .gnb_profile_meta {
        min-width: 0;
        flex: 1;
    }

    .admin-sidebar-kit .gnb_profile_meta strong {
        display: block;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        font-size: 0.875rem;
        font-weight: 600;
        color: var(--color-default-900);
    }

    .admin-sidebar-kit .gnb_profile_meta span {
        display: block;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        font-size: 0.75rem;
        color: var(--color-default-500);
    }

    .admin-sidebar-kit .gnb_profile_logout {
        display: inline-flex;
        height: 2rem;
        width: 2rem;
        align-items: center;
        justify-content: center;
        border: 1px solid var(--color-default-300);
        border-radius: 0.5rem;
        color: var(--color-default-500);
        transition: background-color 0.15s ease, color 0.15s ease;
    }

    .admin-sidebar-kit .gnb_profile_logout:hover {
        background: var(--color-default-100);
        color: var(--color-default-700);
    }

    @media (max-width: 640px) {
        .admin-sidebar-kit {
            width: 100%;
            min-height: 36rem;
        }
    }
</style>

<div class="container-fluid">
    <div class="grid grid-cols-1 gap-base">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Sidebar</h4>
            </div>
            <div class="card-body">
                <p class="mb-4 text-default-400">
                    현재 관리자 사이드메뉴 스타일(2단계 트리 라인, 아코디언, 커스텀 스크롤바 표시 방식)을 그대로 옮긴 UI 샘플입니다.
                </p>

                <div class="sidebar-demo-shell">
                    <aside class="admin-sidebar-kit" aria-label="관리자 사이드바 데모">
                        <h2>
                            <a href="#">
                                <span>A</span>
                                <span>G5 AIF</span>
                            </a>
                        </h2>

                        <div class="gnb_menu_scroll_wrap">
                            <div class="gnb_menu_scroll" tabindex="0">
                                <p class="gnb_label">MAIN MENU</p>

                                <ul class="admin-nav-list" data-admin-nav-root>
                                    <li class="admin-nav-item is-open">
                                        <button type="button" class="admin-nav-trigger" aria-expanded="true">
                                            <span class="admin-nav-trigger-label">환경설정</span>
                                            <span class="admin-nav-caret" aria-hidden="true">⌄</span>
                                        </button>
                                        <div class="admin-nav-panel">
                                            <ul class="admin-nav-sub-list">
                                                <li class="admin-nav-sub-item is-current"><a href="#">기본환경설정</a></li>
                                                <li class="admin-nav-sub-item"><a href="#">권한설정</a></li>
                                                <li class="admin-nav-sub-item"><a href="#">테마설정</a></li>
                                                <li class="admin-nav-sub-item"><a href="#">메뉴설정</a></li>
                                                <li class="admin-nav-sub-item"><a href="#">메일 테스트</a></li>
                                                <li class="admin-nav-sub-item"><a href="#">팝업레이어관리</a></li>
                                                <li class="admin-nav-sub-item"><a href="#">세션파일 일괄삭제</a></li>
                                                <li class="admin-nav-sub-item"><a href="#">캐시파일 일괄삭제</a></li>
                                                <li class="admin-nav-sub-item"><a href="#">캡챠파일 일괄삭제</a></li>
                                                <li class="admin-nav-sub-item"><a href="#">썸네일파일 일괄삭제</a></li>
                                                <li class="admin-nav-sub-item"><a href="#">회원관리파일 일괄삭제</a></li>
                                                <li class="admin-nav-sub-item"><a href="#">phpinfo()</a></li>
                                                <li class="admin-nav-sub-item"><a href="#">Browscap 업데이트</a></li>
                                                <li class="admin-nav-sub-item"><a href="#">접속로그 변환</a></li>
                                                <li class="admin-nav-sub-item"><a href="#">DB업그레이드</a></li>
                                                <li class="admin-nav-sub-item"><a href="#">부가서비스</a></li>
                                            </ul>
                                        </div>
                                    </li>

                                    <li class="admin-nav-item">
                                        <button type="button" class="admin-nav-trigger" aria-expanded="false">
                                            <span class="admin-nav-trigger-label">회원관리</span>
                                            <span class="admin-nav-caret" aria-hidden="true">⌄</span>
                                        </button>
                                        <div class="admin-nav-panel hidden">
                                            <ul class="admin-nav-sub-list">
                                                <li class="admin-nav-sub-item"><a href="#">회원관리</a></li>
                                                <li class="admin-nav-sub-item"><a href="#">회원메일발송</a></li>
                                                <li class="admin-nav-sub-item"><a href="#">접속자집계</a></li>
                                            </ul>
                                        </div>
                                    </li>

                                    <li class="admin-nav-item">
                                        <button type="button" class="admin-nav-trigger" aria-expanded="false">
                                            <span class="admin-nav-trigger-label">게시판관리</span>
                                            <span class="admin-nav-caret" aria-hidden="true">⌄</span>
                                        </button>
                                        <div class="admin-nav-panel hidden">
                                            <ul class="admin-nav-sub-list">
                                                <li class="admin-nav-sub-item"><a href="#">게시판관리</a></li>
                                                <li class="admin-nav-sub-item"><a href="#">인기검색어</a></li>
                                                <li class="admin-nav-sub-item"><a href="#">1:1문의설정</a></li>
                                            </ul>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <div class="gnb_scrollbar" aria-hidden="true">
                                <div class="gnb_scrollbar_thumb"></div>
                            </div>
                        </div>

                        <div class="gnb_profile">
                            <div class="gnb_profile_avatar">A</div>
                            <div class="gnb_profile_meta">
                                <strong>admin</strong>
                                <span>admin@example.com</span>
                            </div>
                            <a href="#" class="gnb_profile_logout" aria-label="로그아웃">↗</a>
                        </div>
                    </aside>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    (function () {
        var navRoots = document.querySelectorAll("[data-admin-nav-root]");

        function setNavItemState(item, opened) {
            item.classList.toggle("is-open", opened);

            var panel = item.querySelector(".admin-nav-panel");
            if (panel) {
                panel.classList.toggle("hidden", !opened);
            }

            var trigger = item.querySelector(".admin-nav-trigger");
            if (trigger) {
                trigger.setAttribute("aria-expanded", opened ? "true" : "false");
            }
        }

        navRoots.forEach(function (navRoot) {
            var navItems = Array.prototype.slice.call(navRoot.querySelectorAll(".admin-nav-item"));

            navItems.forEach(function (item) {
                setNavItemState(item, item.classList.contains("is-open"));
            });

            navRoot.addEventListener("click", function (event) {
                var trigger = event.target.closest(".admin-nav-trigger");
                if (!trigger || !navRoot.contains(trigger)) {
                    return;
                }

                event.preventDefault();

                var activeItem = trigger.closest(".admin-nav-item");
                if (!activeItem) {
                    return;
                }

                var willOpen = !activeItem.classList.contains("is-open");

                navItems.forEach(function (item) {
                    setNavItemState(item, false);
                });

                setNavItemState(activeItem, willOpen);
            });
        });

        var demoSidebars = document.querySelectorAll(".admin-sidebar-kit");

        demoSidebars.forEach(function (sidebar) {
            var scrollWrap = sidebar.querySelector(".gnb_menu_scroll_wrap");
            var menuScroll = sidebar.querySelector(".gnb_menu_scroll");
            var scrollbar = sidebar.querySelector(".gnb_scrollbar");
            var scrollThumb = sidebar.querySelector(".gnb_scrollbar_thumb");
            var hideScrollbarTimer = null;

            if (!scrollWrap || !menuScroll || !scrollbar || !scrollThumb) {
                return;
            }

            function updateMenuScrollbar() {
                var scrollHeight = menuScroll.scrollHeight;
                var clientHeight = menuScroll.clientHeight;
                var canScroll = scrollHeight > clientHeight + 1;

                scrollWrap.classList.toggle("is-scrollable", canScroll);

                if (!canScroll) {
                    scrollThumb.style.height = "0px";
                    scrollThumb.style.transform = "translateY(0)";
                    return;
                }

                var trackHeight = scrollbar.clientHeight;
                if (trackHeight <= 0) {
                    return;
                }

                var ratio = clientHeight / scrollHeight;
                var thumbHeight = Math.max(26, Math.round(trackHeight * ratio));
                var maxThumbTop = Math.max(0, trackHeight - thumbHeight);
                var maxScrollTop = Math.max(1, scrollHeight - clientHeight);
                var thumbTop = Math.round((menuScroll.scrollTop / maxScrollTop) * maxThumbTop);

                scrollThumb.style.height = thumbHeight + "px";
                scrollThumb.style.transform = "translateY(" + thumbTop + "px)";
            }

            function showScrollbar() {
                if (!scrollWrap.classList.contains("is-scrollable")) {
                    return;
                }
                scrollWrap.classList.add("is-scrollbar-visible");
            }

            function scheduleHideScrollbar() {
                if (hideScrollbarTimer) {
                    clearTimeout(hideScrollbarTimer);
                }

                hideScrollbarTimer = setTimeout(function () {
                    if (!scrollWrap.matches(":hover") && !menuScroll.matches(":focus-within")) {
                        scrollWrap.classList.remove("is-scrollbar-visible");
                    }
                }, 320);
            }

            menuScroll.addEventListener("scroll", function () {
                updateMenuScrollbar();
                showScrollbar();
                scheduleHideScrollbar();
            }, { passive: true });

            scrollWrap.addEventListener("mouseenter", function () {
                updateMenuScrollbar();
                showScrollbar();
            });

            scrollWrap.addEventListener("mouseleave", function () {
                scheduleHideScrollbar();
            });

            menuScroll.addEventListener("focusin", function () {
                updateMenuScrollbar();
                showScrollbar();
            });

            menuScroll.addEventListener("focusout", function () {
                scheduleHideScrollbar();
            });

            window.addEventListener("resize", updateMenuScrollbar);

            updateMenuScrollbar();
        });
    })();
</script>

<?php include 'layout/footer.php'; ?>

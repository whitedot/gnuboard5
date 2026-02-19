<?php
$current_page = basename($_SERVER['PHP_SELF']);

function is_active($pages) {
    global $current_page;
    if (is_array($pages)) {
        return in_array($current_page, $pages) ? 'active' : '';
    }
    return $current_page == $pages ? 'active' : '';
}
?>
<aside class="ui-sidebar" id="ui-sidebar">
    <div class="ui-sidebar-logo">
        <span class="text-2xl font-bold ui-guide-title">G5AI</span>
    </div>
    
    <nav class="mt-4">
        <!-- GENERAL -->
        <div class="ui-nav-section-title">General</div>
        <a href="index.php" class="ui-nav-link <?= is_active('index.php') ?>">
            <i class="iconify ui-nav-icon" data-icon="tabler:smart-home"></i>
            Dashboard
        </a>

        <!-- UI COMPONENTS -->
        <div class="ui-nav-section-title">UI Components</div>
        <a href="ui-buttons.php" class="ui-nav-link <?= is_active('ui-buttons.php') ?>">
            <i class="iconify ui-nav-icon" data-icon="tabler:click"></i>
            Buttons
        </a>
        <a href="ui-cards.php" class="ui-nav-link <?= is_active('ui-cards.php') ?>">
            <i class="iconify ui-nav-icon" data-icon="tabler:id"></i>
            Cards
        </a>
        <a href="ui-alerts.php" class="ui-nav-link <?= is_active('ui-alerts.php') ?>">
            <i class="iconify ui-nav-icon" data-icon="tabler:alert-circle"></i>
            Alerts
        </a>
        <a href="ui-badges.php" class="ui-nav-link <?= is_active('ui-badges.php') ?>">
            <i class="iconify ui-nav-icon" data-icon="tabler:label"></i>
            Badges
        </a>
        <a href="ui-modals.php" class="ui-nav-link <?= is_active('ui-modals.php') ?>">
            <i class="iconify ui-nav-icon" data-icon="tabler:window-maximize"></i>
            Modals
        </a>
        <a href="ui-dropdowns.php" class="ui-nav-link <?= is_active('ui-dropdowns.php') ?>">
            <i class="iconify ui-nav-icon" data-icon="tabler:chevron-down"></i>
            Dropdowns
        </a>
        <a href="ui-tabs.php" class="ui-nav-link <?= is_active('ui-tabs.php') ?>">
            <i class="iconify ui-nav-icon" data-icon="tabler:layout-navbar"></i>
            Tabs
        </a>

        <!-- FORMS -->
        <div class="ui-nav-section-title">Forms</div>
        <a href="form-elements.php" class="ui-nav-link <?= is_active('form-elements.php') ?>">
            <i class="iconify ui-nav-icon" data-icon="tabler:forms"></i>
            Elements
        </a>
        <a href="form-validation.php" class="ui-nav-link <?= is_active('form-validation.php') ?>">
            <i class="iconify ui-nav-icon" data-icon="tabler:shield-check"></i>
            Validation
        </a>

        <!-- TABLES -->
        <div class="ui-nav-section-title">Tables</div>
        <a href="tables-static.php" class="ui-nav-link <?= is_active('tables-static.php') ?>">
            <i class="iconify ui-nav-icon" data-icon="tabler:table"></i>
            Static Tables
        </a>

        <!-- ICONS -->
        <div class="ui-nav-section-title">Icons</div>
        <a href="icons-tabler.php" class="ui-nav-link <?= is_active('icons-tabler.php') ?>">
            <i class="iconify ui-nav-icon" data-icon="tabler:sparkles"></i>
            Tabler Icons
        </a>
        <a href="icons-lucide.php" class="ui-nav-link <?= is_active('icons-lucide.php') ?>">
            <i class="iconify ui-nav-icon" data-icon="tabler:bolt"></i>
            Lucide Icons
        </a>
    </nav>
</aside>
<div class="ui-sidebar-backdrop" id="ui-sidebar-backdrop"></div>

<main class="ui-main-content">
    <header class="ui-top-navbar">
        <div class="flex items-center">
            <button
                id="ui-sidebar-toggle"
                type="button"
                class="ui-sidebar-toggle"
                aria-label="사이드바 열기"
                aria-controls="ui-sidebar"
                aria-expanded="false">
                <i class="iconify text-xl" data-icon="tabler:menu-2"></i>
            </button>
            <h2 class="text-lg font-semibold ui-guide-title">UI Guide Dashboard</h2>
        </div>
        <div class="flex items-center gap-2">
            <button
                id="theme-toggle"
                type="button"
                class="btn btn-sm bg-light hover:bg-light-hover text-default-700 ui-theme-toggle"
                aria-label="다크 모드로 전환"
                title="다크 모드로 전환"
                aria-pressed="false">
                <i id="theme-toggle-icon" class="iconify text-base" data-icon="tabler:moon"></i>
                <span id="theme-toggle-label">다크모드</span>
            </button>
        </div>
    </header>
    <div class="ui-content-body">

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
<aside class="ui-sidebar">
    <div class="ui-sidebar-logo">
        <span class="text-2xl font-bold text-indigo-600">Shopall</span>
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

<main class="ui-main-content">
    <header class="ui-top-navbar">
        <div class="flex items-center">
            <h2 class="text-lg font-semibold text-gray-800">UI Guide Dashboard</h2>
        </div>
        <div class="flex items-center gap-4">
            <button class="p-2 text-gray-400 hover:text-gray-600">
                <i class="iconify text-xl" data-icon="tabler:bell"></i>
            </button>
            <button class="p-2 text-gray-400 hover:text-gray-600">
                <i class="iconify text-xl" data-icon="tabler:settings"></i>
            </button>
        </div>
    </header>
    <div class="ui-content-body">

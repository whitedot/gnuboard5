<?php include 'layout/header.php'; ?>
<?php include 'layout/sidebar.php'; ?>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
    <!-- Stats Cards mimicking the image -->
    <div class="card p-6">
        <div class="flex items-center justify-between mb-4">
            <div class="p-2 bg-indigo-50 rounded-lg text-indigo-600">
                <i class="iconify text-2xl" data-icon="tabler:currency-dollar"></i>
            </div>
            <span class="text-xs font-semibold text-green-500 bg-green-50 px-2 py-1 rounded">+41%</span>
        </div>
        <h3 class="text-sm font-medium text-gray-400 mb-1">Revenue</h3>
        <p class="text-2xl font-bold text-gray-800">$ 124,542</p>
    </div>

    <div class="card p-6">
        <div class="flex items-center justify-between mb-4">
            <div class="p-2 bg-blue-50 rounded-lg text-blue-600">
                <i class="iconify text-2xl" data-icon="tabler:shopping-cart"></i>
            </div>
            <span class="text-xs font-semibold text-green-500 bg-green-50 px-2 py-1 rounded">+20%</span>
        </div>
        <h3 class="text-sm font-medium text-gray-400 mb-1">Total Sales</h3>
        <p class="text-2xl font-bold text-gray-800">12,562</p>
    </div>

    <div class="card p-6">
        <div class="flex items-center justify-between mb-4">
            <div class="p-2 bg-cyan-50 rounded-lg text-cyan-600">
                <i class="iconify text-2xl" data-icon="tabler:package"></i>
            </div>
            <span class="text-xs font-semibold text-red-500 bg-red-50 px-2 py-1 rounded">-5%</span>
        </div>
        <h3 class="text-sm font-medium text-gray-400 mb-1">Total Orders</h3>
        <p class="text-2xl font-bold text-gray-800">7,532</p>
    </div>

    <div class="card p-6">
        <div class="flex items-center justify-between mb-4">
            <div class="p-2 bg-green-50 rounded-lg text-green-600">
                <i class="iconify text-2xl" data-icon="tabler:chart-bar"></i>
            </div>
            <span class="text-xs font-semibold text-green-500 bg-green-50 px-2 py-1 rounded">+12%</span>
        </div>
        <h3 class="text-sm font-medium text-gray-400 mb-1">Profit</h3>
        <p class="text-2xl font-bold text-gray-800">$ 60,652</p>
    </div>
</div>

<div class="mt-8">
    <h3 class="text-lg font-bold text-gray-800 mb-4">컴포넌트 바로가기</h3>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="card p-4 hover:shadow-md transition-shadow">
            <h4 class="font-semibold mb-2 flex items-center gap-2">
                <i class="iconify text-indigo-500" data-icon="tabler:forms"></i> Forms
            </h4>
            <ul class="text-sm text-gray-600 space-y-1">
                <li><a href="form-elements.php" class="hover:text-indigo-600">Elements</a></li>
                <li><a href="form-validation.php" class="hover:text-indigo-600">Validation</a></li>
            </ul>
        </div>
        <div class="card p-4 hover:shadow-md transition-shadow">
            <h4 class="font-semibold mb-2 flex items-center gap-2">
                <i class="iconify text-green-500" data-icon="tabler:components"></i> UI Components
            </h4>
            <ul class="text-sm text-gray-600 space-y-1">
                <li><a href="ui-buttons.php" class="hover:text-indigo-600">Buttons</a></li>
                <li><a href="ui-cards.php" class="hover:text-indigo-600">Cards</a></li>
                <li><a href="ui-modals.php" class="hover:text-indigo-600">Modals</a></li>
            </ul>
        </div>
        <div class="card p-4 hover:shadow-md transition-shadow">
            <h4 class="font-semibold mb-2 flex items-center gap-2">
                <i class="iconify text-orange-500" data-icon="tabler:table"></i> Tables & Icons
            </h4>
            <ul class="text-sm text-gray-600 space-y-1">
                <li><a href="tables-static.php" class="hover:text-indigo-600">Static Tables</a></li>
                <li><a href="icons-tabler.php" class="hover:text-indigo-600">Tabler Icons</a></li>
            </ul>
        </div>
    </div>
</div>

<?php include 'layout/footer.php'; ?>
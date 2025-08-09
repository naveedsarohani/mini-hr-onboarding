<header class="w-full h-[60px] flex justify-between items-center px-8 bg-white shadow-sm border-b">
    <a href="/" class="text-2xl font-bold text-blue-500 hover:underline">WebHR</a>

    <div class="flex items-center gap-4">
        <?php if ($user = auth()): ?>
            <button id="openLogsModal" 
                class="px-4 py-2 hover:text-blue-700 transition">
                View Logs
            </button>

            <p class="text-gray-600 font-medium"><?= ucwords($user?->name ?? 'User') ?></p>

            <button onclick="window.location.href='<?= route('auth.logout') ?>'"
                class="px-6 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition">
                Logout
            </button>
        <?php else: ?>
            <button onclick="window.location.href='<?= route('auth.login') ?>'"
                class="px-6 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition">
                Login
            </button>
        <?php endif; ?>
    </div>
</header>

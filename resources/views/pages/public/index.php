<?php ob_start(); ?>

<main class="flex flex-col items-center justify-start bg-gradient-to-br from-gray-50 to-gray-100 px-4">
    <div class="bg-white shadow-lg rounded-lg p-8 max-w-lg w-full text-center border border-gray-200">
        
        <h1 class="text-3xl font-extrabold mb-4">
            Welcome To <span class="text-blue-600">WebHR</span>
        </h1>

        <p class="text-gray-600 mb-6">
            <?php if (auth()): ?>
                You're logged in. Manage your employees with efficiently.
            <?php else: ?>
                Please log in to manage your employees efficiently.
            <?php endif; ?>
        </p>

        <?php if (auth()): ?>
            <a href="<?= route('employees.index') ?>" 
                class="inline-block px-6 py-2 bg-blue-600 text-white font-medium rounded-lg shadow hover:bg-blue-700 transition">
                Manage Employees
            </a>
        <?php else: ?>
            <a href="<?= route('auth.login') ?>" 
                class="inline-block px-6 py-2 bg-blue-600 text-white font-medium rounded-lg shadow hover:bg-blue-700 transition">
                Log In
            </a>
        <?php endif; ?>

    </div>
</main>

<?php
$slot = ob_get_clean();
$title = "Home";
include_once view_path('layouts/master.php');

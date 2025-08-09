<?php ob_start(); ?>

<main class="bg-gray-50 min-h-screen flex items-center justify-center px-4">
    <div class="w-full max-w-md">
        
        <!-- Logo / Title -->
        <h1 onclick="location.href='/'" 
            class="cursor-pointer text-center text-4xl font-extrabold text-blue-500 hover:underline mb-8">
            WebHR
        </h1>

        <!-- Card -->
        <div class="bg-white rounded-2xl shadow-lg p-8">
            <h2 class="text-2xl font-bold text-blue-500 text-center mb-6">Login to Your Account</h2>

            <form action="<?= route('auth.validate-login') ?>" method="post" class="space-y-5">
                
                <!-- Email -->
                <div>
                    <input type="text" 
                        name="email" 
                        placeholder="Enter your email" 
                        value="<?= old('email') ?>" 
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 <?= error('email') ? 'border-red-500 focus:ring-red-500' : 'border-gray-300 focus:ring-blue-500' ?>">
                    <?php if ($message = error('email')): ?>
                        <p class="text-sm text-red-600 mt-1"><?= $message ?></p>
                    <?php endif; ?>
                </div>

                <!-- Password -->
                <div>
                    <input type="password" 
                        name="password" 
                        placeholder="Enter your password" 
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 <?= error('password') ? 'border-red-500 focus:ring-red-500' : 'border-gray-300 focus:ring-blue-500' ?>">
                    <?php if ($message = error('password')): ?>
                        <p class="text-sm text-red-600 mt-1"><?= $message ?></p>
                    <?php endif; ?>
                </div>

                <!-- Submit -->
                <button type="submit" 
                    class="w-full bg-blue-500 hover:bg-blue-600 text-white font-medium py-2.5 rounded-lg transition duration-200">
                    Login
                </button>
            </form>
        </div>
    </div>
</main>

<?php
$slot = ob_get_clean();
$title = "Login | WebHR";
include_once view_path('layouts/auth.php');

<!DOCTYPE html>
<html lang="<?= config('app.locale') ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WebHR | <?= $title ?? 'Dashboard' ?></title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>

<body class="bg-gray-50 text-gray-900 flex flex-col min-h-screen">
    <?php require_once view_path('layouts/partials/header.php') ?>

    <main class="flex-1 p-6 overflow-auto">
        <?= $slot ?? '' ?>
    </main>

    <?php include_once view_path('components/logs/modal.php'); ?>
    <?php require_once view_path('layouts/partials/footer.php') ?>

    <script type="module">
        import showToast from "/js/utils/toast.js";
        <?php if ($toast = session()->get('toast-message')): ?>
            showToast({
                type: "<?= $toast['type'] ?>",
                message: "<?= addslashes($toast['message']) ?>"
            });
        <?php session()->delete('toast-message'); endif; ?>
    </script>
    
    <script src="/js/app.js" type="module"></script>
</body>

</html>

<?php
session()->delete('form-data', 'form-errors');

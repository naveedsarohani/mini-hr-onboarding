<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title><?= "{$statusCode} | {$status}" ?></title>
</head>

<body class="bg-blue-500 flex items-center justify-center min-h-screen">
    <div class="text-center text-white">
        <h1 class="text-6xl font-extrabold"><?= $statusCode ?></h1>
        <p class="text-2xl font-semibold mt-2"><?= $status ?></p>
        <a href="<?= route('home') ?>" class="mt-6 inline-block bg-white text-blue-500 font-medium px-6 py-2 rounded-lg shadow hover:bg-gray-100 transition">
            Go Home
        </a>
    </div>
</body>

</html>
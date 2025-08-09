<?php ob_start(); ?>

<main class="flex justify-center items-start pt-8">
    <div class="bg-white rounded-lg shadow-lg p-8 w-full max-w-2xl">

        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
            <h1 class="text-3xl font-bold text-blue-600">Edit Employee</h1>

            <a href="<?= route('employees.index') ?>?page=<?= session('page') ?>&department=<?= session('dept') ?>"
                class="inline-flex items-center px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
                Go Back
            </a>
        </div>

        <form action="<?= route('employees.update', $employee->id) ?>?page=<?= session('page') ?>&department=<?= session('dept') ?>"
            method="post" enctype="multipart/form-data" class="space-y-5">

            <div>
                <label class="block font-medium mb-1" for="name">Name</label>
                <input type="text" name="name" id="name"
                    value="<?= old('name') ?? $employee->name ?>"
                    placeholder="Full name"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 <?= error('name') ? 'border-red-500 focus:ring-red-500' : 'border-gray-300 focus:ring-blue-500' ?>">
                <?php if ($message = error('name')): ?>
                    <p class="text-sm text-red-600 mt-1"><?= $message ?></p>
                <?php endif; ?>
            </div>

            <div>
                <label class="block font-medium mb-1" for="email">Email</label>
                <input type="email" name="email" id="email"
                    value="<?= old('email') ?? $employee->email ?>"
                    placeholder="Email address"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 <?= error('email') ? 'border-red-500 focus:ring-red-500' : 'border-gray-300 focus:ring-blue-500' ?>">
                <?php if ($message = error('email')): ?>
                    <p class="text-sm text-red-600 mt-1"><?= $message ?></p>
                <?php endif; ?>
            </div>

            <div>
                <label class="block font-medium mb-1" for="department_id">Department</label>
                <select name="department_id" id="department_id"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 <?= error('department_id') ? 'border-red-500 focus:ring-red-500' : 'border-gray-300 focus:ring-blue-500' ?>">
                    <?php foreach ($departments as $dept): ?>
                        <option value="<?= $dept->id ?>" <?= (old('department_id') ?? $employee->department_id) == $dept->id ? 'selected' : '' ?>>
                            <?= ucwords($dept->name) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <?php if ($message = error('department_id')): ?>
                    <p class="text-sm text-red-600 mt-1"><?= $message ?></p>
                <?php endif; ?>
            </div>

            <div>
                <label class="block font-medium mb-1" for="manager">Manager</label>
                <input type="text" name="manager" id="manager"
                    value="<?= old('manager') ?? $employee->manager ?>"
                    placeholder="Manager email"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 <?= error('manager') ? 'border-red-500 focus:ring-red-500' : 'border-gray-300 focus:ring-blue-500' ?>">
                <?php if ($message = error('manager')): ?>
                    <p class="text-sm text-red-600 mt-1"><?= $message ?></p>
                <?php endif; ?>
            </div>

            <div>
                <label class="block font-medium mb-1" for="hire_date">Hire Date</label>
                <input type="date" name="hire_date" id="hire_date"
                    value="<?= old('hire_date') ?? $employee->hire_date ?>"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 <?= error('hire_date') ? 'border-red-500 focus:ring-red-500' : 'border-gray-300 focus:ring-blue-500' ?>">
                <?php if ($message = error('hire_date')): ?>
                    <p class="text-sm text-red-600 mt-1"><?= $message ?></p>
                <?php endif; ?>
            </div>

            <div>
                <label class="block font-medium mb-1" for="resume">
                    Resume <span class="text-gray-400">(Optional)</span>
                </label>
                <input type="file" name="resume" id="resume"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 <?= error('resume') ? 'border-red-500 focus:ring-red-500' : 'border-gray-300 focus:ring-blue-500' ?>">
                <?php if ($message = error('resume')): ?>
                    <p class="text-sm text-red-600 mt-1"><?= $message ?></p>
                <?php elseif ($employee->resume): ?>
                    <p class="text-sm mt-2 text-gray-600">
                        Currently Attached:
                        <a href="<?= route('resume.download', $employee->resume->id) ?>" target="_blank" class="underline text-blue-500 hover:text-blue-600">
                            <?= $employee->resume->name ?>
                        </a>
                    </p>
                <?php endif; ?>
            </div>

            <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 rounded-lg transition">
                Save Changes
            </button>
        </form>
    </div>
</main>

<?php
$slot = ob_get_clean();
$title = "Edit Employee";
include_once view_path('layouts/master.php');
?>
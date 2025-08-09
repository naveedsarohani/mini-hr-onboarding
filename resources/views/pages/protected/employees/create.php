<?php ob_start(); ?>

<main class="flex justify-center items-start pt-8">
    <div class="bg-white rounded-lg shadow-lg p-8 w-full max-w-2xl">
        
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-blue-600">Create Employee</h1>
            <a href="<?= route('employees.index') ?>" 
               class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition">
                Go Back
            </a>
        </div>

        <form action="<?= route('employees.store') ?>?page=<?= session('page') ?>&department=<?= session('dept') ?>" 
              method="post" enctype="multipart/form-data" 
              class="space-y-5">

            <div>
                <label for="name" class="block font-medium text-gray-700 mb-1">Name</label>
                <input type="text" name="name" placeholder="Employee name" 
                       value="<?= old('name') ?>" 
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 <?= error('name') ? 'border-red-500 focus:ring-red-500' : 'border-gray-300 focus:ring-blue-500' ?>">
                <?php if ($message = error('name')): ?>
                    <p class="text-sm text-red-600 mt-1"><?= $message ?></p>
                <?php endif; ?>
            </div>

            <div>
                <label for="email" class="block font-medium text-gray-700 mb-1">Email</label>
                <input type="text" name="email" placeholder="Employee email" 
                       value="<?= old('email') ?>" 
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 <?= error('email') ? 'border-red-500 focus:ring-red-500' : 'border-gray-300 focus:ring-blue-500' ?>">
                <?php if ($message = error('email')): ?>
                    <p class="text-sm text-red-600 mt-1"><?= $message ?></p>
                <?php endif; ?>
            </div>

            <div>
                <label for="department_id" class="block font-medium text-gray-700 mb-1">Department</label>
                <select name="department_id" 
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 <?= error('department_id') ? 'border-red-500 focus:ring-red-500' : 'border-gray-300 focus:ring-blue-500' ?>">
                    <option value="" disabled selected>Select department</option>
                    <?php foreach ($departments as $dept): ?>
                        <option value="<?= $dept->id ?>" <?= $dept->id == old('department_id') ? 'selected' : '' ?>>
                            <?= ucwords($dept->name) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <?php if ($message = error('department_id')): ?>
                    <p class="text-sm text-red-600 mt-1"><?= $message ?></p>
                <?php endif; ?>
            </div>

            <div>
                <label for="manager" class="block font-medium text-gray-700 mb-1">Manager</label>
                <input type="text" name="manager" placeholder="Manager email" 
                       value="<?= old('manager') ?>" 
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 <?= error('manager') ? 'border-red-500 focus:ring-red-500' : 'border-gray-300 focus:ring-blue-500' ?>">
                <?php if ($message = error('manager')): ?>
                    <p class="text-sm text-red-600 mt-1"><?= $message ?></p>
                <?php endif; ?>
            </div>

            <div>
                <label for="hire_date" class="block font-medium text-gray-700 mb-1">Hire Date</label>
                <input type="date" name="hire_date" 
                       value="<?= old('hire_date') ?>" 
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 <?= error('hire_date') ? 'border-red-500 focus:ring-red-500' : 'border-gray-300 focus:ring-blue-500' ?>">
                <?php if ($message = error('hire_date')): ?>
                    <p class="text-sm text-red-600 mt-1"><?= $message ?></p>
                <?php endif; ?>
            </div>

            <div>
                <label for="resume" class="block font-medium text-gray-700 mb-1">
                    Resume <small class="text-gray-400">(Optional)</small>
                </label>
                <input type="file" name="resume" 
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 <?= error('resume') ? 'border-red-500 focus:ring-red-500' : 'border-gray-300 focus:ring-blue-500' ?>">
                <?php if ($message = error('resume')): ?>
                    <p class="text-sm text-red-600 mt-1"><?= $message ?></p>
                <?php endif; ?>
            </div>

            <div>
                <button type="submit" 
                        class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition">
                    Create
                </button>
            </div>
        </form>
    </div>
</main>

<?php
$slot = ob_get_clean();
$title = "Create Employee";
include_once view_path('layouts/master.php');

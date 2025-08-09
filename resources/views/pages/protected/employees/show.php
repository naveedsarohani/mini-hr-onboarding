<?php ob_start(); ?>

<main class="flex justify-center items-start pt-8">
    <div class="bg-white rounded-lg shadow-lg p-8 w-full max-w-2xl">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
            <h1 class="text-3xl font-bold text-blue-600">Employee Details</h1>

            <a href="<?= route('employees.index') ?>"
                class="inline-flex items-center px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
                Go Back
            </a>
        </div>

        <div class="space-y-5">
            <div>
                <span class="block text-sm text-gray-500 uppercase tracking-wide">Name</span>
                <p class="text-lg font-semibold text-gray-800"><?= __($employee->name) ?></p>
            </div>

            <div>
                <span class="block text-sm text-gray-500 uppercase tracking-wide">Email</span>
                <p class="text-lg font-semibold text-gray-800"><?= __($employee->email) ?></p>
            </div>

            <div>
                <span class="block text-sm text-gray-500 uppercase tracking-wide">Department</span>
                <p class="text-lg font-semibold text-gray-800">
                    <?= __($employee->department->name ?? 'N/A') ?>
                </p>
            </div>

            <div>
                <span class="block text-sm text-gray-500 uppercase tracking-wide">Manager</span>
                <p class="text-lg font-semibold text-gray-800"><?= __($employee->manager) ?></p>
            </div>

            <div>
                <span class="block text-sm text-gray-500 uppercase tracking-wide">Hire Date</span>
                <p class="text-lg font-semibold text-gray-800"><?= formatDate($employee->hire_date) ?></p>
            </div>

            <div>
                <span class="block text-sm text-gray-500 uppercase tracking-wide">Resume</span>
                <?php if ($employee->resume): ?>
                    <a href="<?= route('resume.download', $employee->resume->id) ?>" target="_blank"
                        class="text-blue-500 hover:text-blue-600 underline">
                        <?= $employee->resume->name ?>
                    </a>
                <?php else: ?>
                    <p class="text-gray-500">Not uploaded</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>

<?php
$slot = ob_get_clean();
$title = "View Employee";
include_once view_path('layouts/master.php');
?>
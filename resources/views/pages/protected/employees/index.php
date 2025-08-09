<?php ob_start(); ?>

<main>
    <h1 class="text-2xl font-bold mb-4 text-blue-600">Employees</h1>

    <div class="flex justify-between items-center mb-4">
        <div class="flex items-center space-x-2">
            <input type="text" id="search" placeholder="Search employee name"
                class="border border-gray-300 rounded px-4 py-2 w-64" />

            <select id="department" class="border border-gray-300 rounded px-3 py-2">
                <option value="">All Departments</option>
                <?php foreach ($departments as $dept): ?>
                    <option value="<?= $dept->id ?>"><?= __($dept->name) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="flex items-center space-x-2">
            <a href="<?= route('employees.create') ?>" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700">
                Add Employee
            </a>
        </div>
    </div>

    <div id="employee-table-container" class="relative overflow-x-auto">
        <div id="employee-table-content" class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200 rounded">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="text-left px-4 py-2 border-b">#</th>
                        <th class="text-left px-4 py-2 border-b">Name</th>
                        <th class="text-left px-4 py-2 border-b">Email</th>
                        <th class="text-left px-4 py-2 border-b">Department</th>
                        <th class="text-left px-4 py-2 border-b">Manager</th>
                        <th class="text-left px-4 py-2 border-b">Hire Date</th>
                        <th class="text-left px-4 py-2 border-b">Actions</th>
                    </tr>
                </thead>

                <div class="relative">
                    <tbody>
                        <tr class="loading-overlay">
                            <td colspan="7" class="text-center py-2 text-gray-600 bg-gray-50 animate-pulse">Loading...</td>
                        </tr>
                    </tbody>
                </div>
            </table>
        </div>
    </div>
</main>

<?php
$slot = ob_get_clean();
$title = "Employees";
include_once view_path('layouts/master.php');
?>
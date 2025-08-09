<?php
session()->set('page', $employees['current_page']);
session()->set('dept', $employees['dept']);
?>

<div class="overflow-x-auto bg-white rounded-xl shadow-lg">
    <table class="min-w-full text-sm text-left border border-gray-200">
        <thead class="bg-gray-100 text-gray-700 uppercase text-xs">
            <tr>
                <th class="px-4 py-3 border-b">#</th>
                <th class="px-4 py-3 border-b">Name</th>
                <th class="px-4 py-3 border-b">Email</th>
                <th class="px-4 py-3 border-b">Department</th>
                <th class="px-4 py-3 border-b">Manager</th>
                <th class="px-4 py-3 border-b">Hire Date</th>
                <th class="px-4 py-3 border-b text-center">Actions</th>
            </tr>
        </thead>
        <tbody class="relative divide-y divide-gray-200">
            <?php if (!empty($employees['data'])) : ?>
                <?php foreach ($employees['data'] as $index => $employee): ?>
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-4 py-3">
                            <?= $index + 1 + (($employees['current_page'] - 1) * $employees['per_page']) ?>
                        </td>
                        <td class="px-4 py-3 font-medium text-gray-900">
                            <?= __($employee['name']) ?>
                        </td>
                        <td class="px-4 py-3 text-gray-700">
                            <?= __($employee['email']) ?>
                        </td>
                        <td class="px-4 py-3">
                            <?= __($employee['department']['name']) ?>
                        </td>
                        <td class="px-4 py-3">
                            <?= __($employee['manager']) ?>
                        </td>
                        <td class="px-4 py-3">
                            <?= __(formatDate($employee['hire_date'])) ?>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <div class="flex justify-center space-x-3">
                                <a href="<?= route('employees.show', $employee['id']) ?>"
                                    class="text-blue-600 hover:text-blue-800 font-medium">View</a>
                                <a href="<?= route('employees.edit', $employee['id']) ?>"
                                    class="text-yellow-500 hover:text-yellow-600 font-medium">Edit</a>
                                <form action="<?= route('employees.delete', $employee['id']) ?>?page=<?= $employees['current_page'] ?? 1 ?>&department=<?= $employees['dept'] ?? '' ?>"
                                    method="POST"
                                    onsubmit="return confirm('Are you sure you want to delete this employee?')">
                                    <button type="submit"
                                        class="text-red-600 hover:text-red-800 font-medium">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7" class="px-4 py-2 text-center text-gray-500">
                        No employees found.
                    </td>
                </tr>
            <?php endif; ?>

            <tr class="loading-overlay-after hidden absolute top-0 left-0 flex justify-center items-center w-full h-full">
                <td colspan="7" class="min-w-full text-center py-2 text-gray-600 bg-gray-50 animate-pulse h-full flex items-center justify-center">Loading...</td>
            </tr>
        </tbody>
    </table>
</div>

<?php if ($employees['last_page'] > 1): ?>
    <div class="mt-6 flex justify-center space-x-2">
        <?php for ($i = 1; $i <= $employees['last_page']; $i++): ?>
            <button
                data-page="<?= $i ?>"
                <?= $i === $employees['current_page'] ? 'disabled' : '' ?>
                class="pagination-link px-3 py-1 rounded-lg border 
                   <?= $i === $employees['current_page']
                        ? 'bg-blue-600 text-white border-blue-600'
                        : 'bg-white text-blue-600 border-gray-300 hover:bg-blue-50' ?>">
                <?= $i ?>
            </button>
        <?php endfor; ?>
    </div>
<?php endif; ?>
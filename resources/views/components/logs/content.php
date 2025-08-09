<?php if (!empty($logs)): ?>
    <div class="space-y-4">
        <?php foreach ($logs as $i => $log): ?>
            <div class="flex justify-between items-start pb-3 <?= $i < count($logs) - 1 ? 'border-b border-gray-200' : '' ?>">
                <div class="space-y-1">
                    <div class="flex items-center gap-2">

                        <?php
                        $badgeClasses = match ($log['action']) {
                            "UPLOAD_DOCUMENT" => "bg-blue-100 text-blue-700",
                            "CREATE_EMPLOYEE" => "bg-green-100 text-green-700",
                            default => "bg-gray-100 text-gray-700"
                        };
                        ?>
                        <span class="px-2 py-0.5 text-xs font-medium rounded <?= $badgeClasses ?>">
                            <?= str_replace('_', ' ', $log['action']) ?>
                        </span>
                    </div>

                    <p class="text-sm text-gray-600">
                        <?php if ($log['action'] === "UPLOAD_DOCUMENT" && !empty($log['file_name'])): ?>
                            File:
                            <a href="<?= route('resume.download', $log['file_id']) ?>" class="text-blue-500 hover:underline" target="_blank">
                                <?= __($log['file_name']) ?>
                            </a>
                        <?php elseif ($log['action'] === "CREATE_EMPLOYEE" && !empty($log['employee_id'])): ?>
                            Employee:
                            <a href="<?= route('employees.show', $log['employee_id']) ?>" class="text-blue-500 hover:underline">
                                <?= __($log['employee_name']) ?>
                            </a>
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </p>
                    <p class="text-xs text-gray-400">
                        <?= date("Y-m-d h:i A", $log['timestamp']->toDateTime()->getTimestamp()) ?>
                    </p>
                </div>

                <div class="flex flex-col items-end gap-2">
                    <button
                        class="px-3 py-1 text-sm bg-red-500 text-white rounded hover:bg-red-600 deleteLogBtn"
                        data-id="<?= $log['_id'] ?>">
                        Delete
                    </button>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <p class="text-gray-500">No logs found.</p>
<?php endif; ?>
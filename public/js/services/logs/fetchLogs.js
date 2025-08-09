const $logContent = $('#logsContent');

function fetchLogs() {
    $logContent.html(`<p class="text-gray-500 dark:text-gray-400">Loading logs...</p>`);

    $.ajax({
        url: "/api/logs/",
        method: 'GET',
        headers: {
            'Authorization': true,
        },
        success: function (response) {
            $logContent.html(response);
        },
        error: function () {
            $logContent.html('<div class="p-4 text-red-600">Error loading data.</div>');
        },
    });
}

export default fetchLogs;
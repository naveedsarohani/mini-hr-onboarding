const $search = $('#search');
const $department = $('#department');
const $tableContent = $('#employee-table-content');
let $loading = $('.loading-overlay');

function fetchEmployees(page = 1) {
    const search = $search.val();
    const department = $department.val();

    if ($department || $search) {
        $loading = $('.loading-overlay-after');
    }

    $loading.removeClass('hidden');

    $.ajax({
        url: "/api/employees",
        method: 'GET',
        headers: {
            'Authorization': true,
        },
        data: {
            page,
            search,
            department
        },
        success: function (response) {
            $tableContent.html(response);
        },
        error: function (error) {
            $tableContent.html('<div class="p-4 text-red-600">Error loading data.</div>');
        },
        complete: function () {
            $loading.addClass('hidden');
        }
    });
}

export default fetchEmployees;
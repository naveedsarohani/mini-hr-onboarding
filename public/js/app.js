import fetchEmployees from "./services/employees/fetchEmployees.js";
import fetchLogs from "./services/logs/fetchLogs.js";
import deleteLog from "./services/logs/deleteLog.js";
import debounce from "./utils/debounce.js";

const $search = $('#search');
const $department = $('#department');
const $tableContent = $('#employee-table-content');
const $logsModal = $('#logsModal');
const $logsContent = $('#logsContent');
const $openLogsModal = $('#openLogsModal');
const $closeLogsModal = $('#closeLogsModal');

$(document).ready(() => {
    fetchEmployees();

    $search.on('input', debounce(() => fetchEmployees(), 700));
    $department.on('change', () => fetchEmployees());

    $openLogsModal.click(function () {
        $logsModal.removeClass("hidden");
        fetchLogs();
    });

    $closeLogsModal.click(function () {
        $logsModal.addClass("hidden");
        $logsContent.html("");
    });

    $logsContent.on('click', '.deleteLogBtn', function () {
        const id = $(this).data('id');
        deleteLog(id);
    });

    $tableContent.on('click', '.pagination-link', function (e) {
        e.preventDefault();
        const page = $(this).data('page');
        fetchEmployees(page);
    });
});
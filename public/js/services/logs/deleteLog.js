import showToast from "../../utils/toast.js";
import fetchLogs from "./fetchLogs.js";

function deleteLog(id) {
    if (!confirm("Delete this log?")) return;

    $.ajax({
        url: `/api/logs/delete/${id}`,
        method: 'POST',
        headers: {
            'Authorization': true,
        },
        success: function (res) {
            if (res.success) {
                showToast({ type: 'success', message: 'Log has been deleted' });
                fetchLogs();
            } else {
                showToast({ type: 'error', message: 'Failed to delete log' });
            }
        },
        error: function (error) {
           showToast({ type: 'error', message: error?.message ?? 'Error deleting log' });
        }
    });
}

export default deleteLog;
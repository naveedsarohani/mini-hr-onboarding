<div id="logsModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 transition-opacity duration-200">
    <div class="bg-white rounded-lg shadow-2xl w-full max-w-2xl max-h-[80vh] flex flex-col transform scale-95 transition-transform duration-200 overflow-hidden">

        <div class="flex justify-between items-center border-b px-5 py-3 bg-gray-50 sticky top-0 z-10">
            <h2 class="text-lg font-bold text-gray-800">Logs</h2>
            <button id="closeLogsModal" class="text-gray-400 hover:text-red-500 text-3xl font-bold">
                &times;
            </button>
        </div>

        <div id="logsContent" class="flex-1 overflow-y-auto p-5 space-y-3 text-sm text-gray-700">
            <p class="animate-pulse text-gray-400">Loading logs...</p>
        </div>
    </div>
</div>

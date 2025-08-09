<?php

namespace App\Controllers\Api;

use App\Logs\Log;
use Core\Request;

class LogController
{
    public function index(Request $request, Log $log)
    {
        $logs = $log->get();
        return view('components.logs.content', compact('logs'));
    }
    
    public function delete(Request $request, Log $log, $id)
    {
        if (!$log->delete($id)) {
            return response(400, 'Failed to delete logs');
        }

        return response(200, 'Log has been deleted');
    }
}

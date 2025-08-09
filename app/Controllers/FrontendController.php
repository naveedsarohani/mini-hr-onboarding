<?php

namespace App\Controllers;

use App\Models\Resume;
use Core\Dropbox;
use Core\Request;

class FrontendController
{
    public function index()
    {
        return view('pages.public.index');
    }

    public function downloadResume(Request $request, Dropbox $drive, string $id)
    {
        if (!$resume = Resume::find($id)) {
            return redirect()->fallback('404', 'Resume Not Found');
        }

        return $drive->download($resume->path, $resume->name);
    }
}

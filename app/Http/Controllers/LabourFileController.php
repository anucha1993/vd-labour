<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;

class LabourFileController extends Controller
{
    public function show($year, $month, $filename)
    {
        $path = "LABOURS/$year/$month/$filename";

        if (!Storage::disk('labour_documents')->exists($path)) {
            abort(404, 'File not found');
        }

        return Storage::disk('labour_documents')->response($path);
    }
}

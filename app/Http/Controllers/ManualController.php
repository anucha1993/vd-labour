<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ManualController extends Controller
{
    // ไม่ต้อง login ก็เข้าได้ (comment ออก)
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    /**
     * Display manual page with all PDF files from public/manual directory
     */
    public function index()
    {
        $manualPath = public_path('manuals');
        
        // Create directory if not exists
        if (!File::exists($manualPath)) {
            File::makeDirectory($manualPath, 0755, true);
        }
        
        // Get all PDF files
        $files = File::files($manualPath);
        $manuals = [];
        
        foreach ($files as $file) {
            if ($file->getExtension() === 'pdf') {
                $manuals[] = [
                    'name' => $file->getFilename(),
                    'path' => 'manuals/' . $file->getFilename(),
                    'size' => $this->formatBytes($file->getSize()),
                    'modified' => date('d/m/Y H:i', $file->getMTime())
                ];
            }
        }
        
        // Sort by modified date (newest first)
        usort($manuals, function($a, $b) {
            return strcmp($b['modified'], $a['modified']);
        });
        
        return view('manual.index', compact('manuals'));
    }
    
    /**
     * Format file size
     */
    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }
}

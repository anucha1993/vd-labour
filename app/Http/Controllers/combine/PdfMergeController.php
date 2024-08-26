<?php

namespace App\Http\Controllers\Combine;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Jurosh\PDFMerge\PDFMerger; // นำเข้าคลาส PDFMerger

class PdfMergeController extends Controller
{
    public function mergePdfs(Request $request)
    {
        // รับไฟล์ที่เลือกจาก request
        $selectedFiles = $request->input('checkNum', []);
        $pageOrders = $request->input('no', []);

        if (empty($selectedFiles) || empty($pageOrders)) {
            return back()->with('error', 'กรุณาเลือกไฟล์ PDF และระบุลำดับเพื่อรวม');
        }

        // สร้าง PDFMerger object
        $pdfMerger = new PDFMerger();

        // Loop ผ่านไฟล์ PDF แต่ละไฟล์ที่เลือก
        foreach ($selectedFiles as $file) {
            $filePath = Storage::disk('public')->path($file);

            if (file_exists($filePath)) {
                // เพิ่มไฟล์ PDF ลงใน PDFMerger
                $pdfMerger->addPDF($filePath, 'all', 'vertical'); // หรือเปลี่ยนตามที่ต้องการ
            }
        }

        // สร้างไฟล์ PDF ที่รวมแล้ว
        $outputFile = public_path('pdf/merged.pdf');
        $pdfMerger->merge('file', $outputFile);

        // ดาวน์โหลดไฟล์ที่รวมกันแล้ว
        return response()->download($outputFile)->deleteFileAfterSend(true);
    }
}

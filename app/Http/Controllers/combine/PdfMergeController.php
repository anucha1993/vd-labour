<?php

namespace App\Http\Controllers\Combine;

use Illuminate\Http\Request;
use App\Models\labours\labourModel;
use App\Http\Controllers\Controller;

class PdfMergeController extends Controller
{
    // public function mergePdfs(Request $request)
    // {
    //     // รายชื่อไฟล์ PDF ที่จะรวม
    //     $pdfFiles = [
    //         '\\\\192.168.10.100\\public\\2024\\TEST\\BRC_ANUCHA_YOTHANAN.pdf',
    //         '\\\\192.168.10.100\\public\\2024\\TEST\\CER_ANUCHA_YOTHANAN.pdf'
    //     ];
    //     dd($pdfFiles);
        
    //     // เส้นทางสำหรับไฟล์ PDF ที่รวมแล้ว
    //     $outputFile = '\\\\192.168.10.100\\public\\2024\\TEST\\merged.pdf';
    
    //     // ตั้งค่าตัวแปร PATH
    //     putenv('PATH=C:\\Program Files (x86)\\PDFtk\\bin;' . getenv('PATH'));
    
    //     // ตรวจสอบว่าไฟล์มีอยู่
    //     foreach ($pdfFiles as $file) {
    //         if (!file_exists($file)) {
    //             return response()->json(['message' => "File not found: $file"], 404);
    //         }
    //     }

    //     // สร้างคำสั่ง pdftk
    //     $command = 'pdftk ' . implode(' ', array_map('escapeshellarg', $pdfFiles)) . ' cat output ' . escapeshellarg($outputFile);
    
    //     // เรียกใช้คำสั่ง pdftk
    //     exec($command . ' 2>&1', $output, $returnVar);
    
    //     if ($returnVar === 0) {
    //         // ส่งไฟล์ PDF ที่รวมแล้วเป็นการดาวน์โหลด
    //         return response()->download($outputFile)->deleteFileAfterSend(true);
    //     } else {
    //         return response()->json(['message' => 'Failed to merge PDFs.', 'error' => implode("\n", $output)], 500);
    //     }
    // }

    public function mergePdfs(Request $request)
{
    $nos = $request->input('no', []);
    $paths = $request->input('checkNum', []);

    // ปรับ path ให้ตรงกับ alias ที่ตั้งใน Apache
    $pdfFiles = [];
    foreach ($nos as $index => $no) {
        if (isset($paths[$index])) {
            // ใช้ trim เพื่อกำจัดช่องว่างที่ไม่จำเป็น และ str_replace เพื่อแปลง backslashes เป็น forward slashes
            $filePath = 'D:/LABOURS/' . trim(str_replace('\\', '/', $paths[$index]));

            // Debugging: แสดง path เพื่อดูว่ามีเส้นทางที่ถูกต้องหรือไม่
            if (!file_exists($filePath)) {
                return response()->json(['message' => "File not found: $filePath"], 404);
            }

            $pdfFiles[$no] = $filePath;
        }
    }

    // เรียงไฟล์ตามลำดับ
    ksort($pdfFiles);
    $pdfFiles = array_values($pdfFiles);

    // ตรวจสอบว่าไฟล์ที่จะรวมมีไฟล์อย่างน้อย 1 ไฟล์หรือไม่
    if (count($pdfFiles) === 0) {
        return response()->json(['message' => 'No valid PDF files found.'], 400);
    }

    $labourModel = labourModel::where('labour_id', $request->labour_id)->first();
    if (!$labourModel) {
        return response()->json(['message' => 'Labour model not found.'], 404);
    }

    // เส้นทางสำหรับไฟล์ PDF ที่รวมแล้ว
    $outputFile = 'D:/LABOURS/DOC_' . $labourModel->labour_firstname . '_' . $labourModel->labour_lastname . '.pdf';

    // ตั้งค่าตัวแปร PATH
    putenv('PATH=C:\\Program Files (x86)\\PDFtk\\bin;' . getenv('PATH'));

    // สร้างคำสั่ง pdftk
    $command = 'pdftk ' . implode(' ', array_map('escapeshellarg', $pdfFiles)) . ' cat output ' . escapeshellarg($outputFile);

    // เรียกใช้คำสั่ง pdftk
    exec($command . ' 2>&1', $output, $returnVar);

    if ($returnVar === 0) {
        // ส่งไฟล์ PDF ที่รวมแล้วเป็นการดาวน์โหลด
        return response()->download($outputFile)->deleteFileAfterSend(true);
    } else {
        return response()->json(['message' => 'Failed to merge PDFs.', 'error' => implode("\n", $output)], 500);
    }
}

    
    }
    

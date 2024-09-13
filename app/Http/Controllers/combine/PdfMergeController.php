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
                // แปลง path ให้ตรงกับโครงสร้างที่ใช้งานใน Windows
                $filePath = 'D:/LABOURS/' . $paths[$index]; // ใช้ path ของ D:/LABOURS ตามที่ Alias ชี้ไป
                if (file_exists($filePath)) {
                    $pdfFiles[$no] = $filePath;
                } else {
                    return response()->json(['message' => "File not found: $filePath"], 404);
                }
            }
        }
    
        $labourModel = labourModel::where('labour_id',$request->labour_id)->first();
    
        // เรียงไฟล์ตามลำดับ
        ksort($pdfFiles);
        $pdfFiles = array_values($pdfFiles);
        
        // เส้นทางสำหรับไฟล์ PDF ที่รวมแล้ว
        $outputFile = 'D:/LABOURS/' . 'DOC_' . $labourModel->labour_firstname . '_' . $labourModel->labour_lastname . '.pdf';
    
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

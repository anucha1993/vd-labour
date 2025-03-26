<?php

namespace App\Http\Controllers\Combine;

use Illuminate\Http\Request;
use App\Models\labours\labourModel;
use App\Http\Controllers\Controller;

class PdfMergeController extends Controller
{
   
    public function mergePdfs(Request $request)
{

    
    // $nos = $request->input('no', []);
    // $paths = $request->input('checkNum', []);
    // // ปรับ path ให้ตรงกับ alias ที่ตั้งใน Apache
    // $pdfFiles = [];
    // foreach ($nos as $index => $no) {
    //     if (isset($paths[$index])) {
    //         // ใช้ trim เพื่อกำจัดช่องว่างที่ไม่จำเป็น และ str_replace เพื่อแปลง backslashes เป็น forward slashes
    //         $filePath =  trim(str_replace('\\', '/', $paths[$index]));

    //         // Debugging: แสดง path เพื่อดูว่ามีเส้นทางที่ถูกต้องหรือไม่
    //         if (!file_exists($filePath)) {
    //             return response()->json(['message' => "File not found: $filePath"], 404);
    //         }
    //         $pdfFiles[$no] = $filePath;
    //     }
    // }

//     $nos = $request->input('no', []);
// $paths = $request->input('checkNum', []);

// $pdfFiles = [];
// foreach ($paths as $index => $path) {
//     $filePath = trim(str_replace('\\', '/', $path));
//     if (!file_exists($filePath)) {
//         return response()->json(['message' => "File not found: $filePath"], 404);
//     }
//     $pdfFiles[$index] = $filePath;
// }


$nos = $request->input('no', []);
$paths = $request->input('checkNum', []);

// กรองค่า null ออกจาก $nos
$selectedNos = array_filter($nos, function($value) {
    return $value !== null;
});

// สร้าง array ใหม่เพื่อเก็บไฟล์ตามลำดับที่เลือก
$orderedPaths = [];

// วนลูปตามค่าใน $selectedNos
foreach ($selectedNos as $no) {
    // แปลง $no เป็น integer และลบ 1 เพื่อให้ตรงกับ index ของ $paths
    $index = (int) $no - 1;

    // ตรวจสอบว่า index มีอยู่ใน $paths หรือไม่
    if (isset($paths[$index])) {
        $orderedPaths[] = $paths[$index];
    }
}

// แปลง backslashes เป็น forward slashes และตรวจสอบไฟล์
$pdfFiles = [];
foreach ($orderedPaths as $path) {
    $filePath = trim(str_replace('\\', '/', $path));
    if (!file_exists($filePath)) {
        return response()->json(['message' => "File not found: $filePath"], 404);
    }
    $pdfFiles[] = $filePath;
}



//dd($nos,$paths, $pdfFiles);





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
    $outputFile =  $labourModel->labour_firstname . '_' . $labourModel->labour_lastname . '.pdf';

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
    

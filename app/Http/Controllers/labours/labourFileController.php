<?php

namespace App\Http\Controllers\labours;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\files\labourFileModel;
use Illuminate\Support\Facades\Storage;

class labourFileController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function delete(Request $request)
    {
        // Laravel Storage ใช้ / เสมอ และ path ต้องสัมพันธ์กับ storage/app/public
        $path = str_replace('\\', '/', $request->path); // แก้ \ เป็น /
        $path = ltrim($path, '/'); // ตัด / นำหน้า (ถ้ามี)
        if (strpos($path, 'storage/') === 0) {
            $path = substr($path, strlen('storage/'));
        }

        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path); // ลบไฟล์ที่ระบุ

            labourFileModel::where('labour_file_id', $request->fileId)->update([
                'labour_file_path' => null,
                'updated_by' => auth()->id()
            ]);

            return response()->json(['success' => 'Deleted File Path ' . $request->path . ' Successfully.']);
        } else {
            labourFileModel::where('labour_file_id', $request->fileId)->update([
                'labour_file_path' => null,
                'updated_by' => auth()->id()
            ]);
            return response()->json(['error' => 'Cannot Delete File Path ' . $request->path . ' Error.']);
        }
    }
}

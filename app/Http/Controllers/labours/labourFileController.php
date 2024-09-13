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
        $path = str_replace('/', DIRECTORY_SEPARATOR, $request->path); // แปลงสแลชให้เป็นแบบถูกต้อง

        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path); // ลบไฟล์ที่ระบุ

            labourFileModel::where('labour_file_id', $request->fileId)->update(['labour_file_path' => null]);

            return response()->json(['success' => 'Deleted File Path ' . $request->path . ' Successfully.']);
        } else {
            labourFileModel::where('labour_file_id', $request->fileId)->update(['labour_file_path' => null]);
            return response()->json(['error' => 'Cannot Delete File Path ' . $request->path . ' Error.']);
        }
    }
}

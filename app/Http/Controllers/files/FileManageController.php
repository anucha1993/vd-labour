<?php

namespace App\Http\Controllers\files;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\files\fileManageModel;
use App\Models\files\listFileModel;

class FileManageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:view file-manage', ['only' => ['index', 'show']]);
        $this->middleware('permission:create file-manage', ['only' => ['create', 'store', 'addListFile']]);
        $this->middleware('permission:update file-manage', ['only' => ['edit', 'update', 'updateListFile']]);
        $this->middleware('permission:delete file-manage', ['only' => ['destroy', 'destroyListFile']]);
    }

    // แสดงรายการประเภทเอกสารทั้งหมด
    public function index()
    {
        $fileManages = fileManageModel::withCount('listFiles')->latest()->get();
        return view('files.index', compact('fileManages'));
    }

    // แสดงฟอร์มสร้างประเภทเอกสารใหม่
    public function create()
    {
        return view('files.create');
    }

    // บันทึกประเภทเอกสารใหม่
    public function store(Request $request)
    {
        $request->validate([
            'file_manage_name' => 'required|string|max:255',
            'file_manage_status' => 'required|in:active,inactive',
        ]);

        fileManageModel::create($request->all());

        return redirect()->route('file-manage.index')->with('success', 'เพิ่มประเภทเอกสารเรียบร้อยแล้ว');
    }

    // แสดงรายละเอียดและรายการไฟล์
    public function show($id)
    {
        $fileManage = fileManageModel::with('listFiles')->findOrFail($id);
        return view('files.show', compact('fileManage'));
    }

    // แสดงฟอร์มแก้ไขประเภทเอกสาร
    public function edit($id)
    {
        $fileManage = fileManageModel::findOrFail($id);
        return view('files.edit', compact('fileManage'));
    }

    // อัพเดทประเภทเอกสาร
    public function update(Request $request, $id)
    {
        $request->validate([
            'file_manage_name' => 'required|string|max:255',
            'file_manage_status' => 'required|in:active,inactive',
        ]);

        $fileManage = fileManageModel::findOrFail($id);
        $fileManage->update($request->all());

        return redirect()->route('file-manage.index')->with('success', 'แก้ไขประเภทเอกสารเรียบร้อยแล้ว');
    }

    // ลบประเภทเอกสาร
    public function destroy($id)
    {
        $fileManage = fileManageModel::findOrFail($id);
        $fileManage->delete();

        return redirect()->route('file-manage.index')->with('success', 'ลบประเภทเอกสารเรียบร้อยแล้ว');
    }

    // เพิ่มรายการไฟล์
    public function addListFile(Request $request, $id)
    {
        $request->validate([
            'list_file_name' => 'required|string|max:255',
            'list_file_note' => 'nullable|string|max:255',
            'list_file_status' => 'required|in:active,inactive',
        ]);

        listFileModel::create([
            'file_manage_id' => $id,
            'list_file_name' => $request->list_file_name,
            'list_file_note' => $request->list_file_note,
            'list_file_status' => $request->list_file_status,
        ]);

        return redirect()->route('file-manage.show', $id)->with('success', 'เพิ่มรายการไฟล์เรียบร้อยแล้ว');
    }

    // แก้ไขรายการไฟล์
    public function updateListFile(Request $request, $id, $listFileId)
    {
        $request->validate([
            'list_file_name' => 'required|string|max:255',
            'list_file_note' => 'nullable|string|max:255',
            'list_file_status' => 'required|in:active,inactive',
        ]);

        $listFile = listFileModel::findOrFail($listFileId);
        $listFile->update($request->all());

        return redirect()->route('file-manage.show', $id)->with('success', 'แก้ไขรายการไฟล์เรียบร้อยแล้ว');
    }

    // ลบรายการไฟล์
    public function destroyListFile($id, $listFileId)
    {
        $listFile = listFileModel::findOrFail($listFileId);
        $listFile->delete();

        return redirect()->route('file-manage.show', $id)->with('success', 'ลบรายการไฟล์เรียบร้อยแล้ว');
    }
}

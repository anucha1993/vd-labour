<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\JobLeadNotificationService;
use App\Models\jobs\JobLeadNotificationModel;

class JobLeadNotificationController extends Controller
{
    protected $notificationService;
    
    public function __construct(JobLeadNotificationService $notificationService)
    {
        $this->middleware('auth');
        $this->notificationService = $notificationService;
    }
    
    /**
     * แสดงรายการการแจ้งเตือนทั้งหมด
     */
    public function index()
    {
        $notifications = JobLeadNotificationModel::with(['jobLead.lead', 'jobLead.job', 'respondedBy'])
            ->latest('sent_at')
            ->paginate(20);
            
        $unreadCount = $this->notificationService->getUnreadCount();
        
        return view('job-lead-notifications.index', compact('notifications', 'unreadCount'));
    }
    
    /**
     * ดึงการแจ้งเตือนที่ยังไม่ได้อ่าน (สำหรับ AJAX)
     */
    public function unread()
    {
        $notifications = $this->notificationService->getUnreadNotifications();
        $count = $this->notificationService->getUnreadCount();
        
        return response()->json([
            'notifications' => $notifications,
            'count' => $count
        ]);
    }
    
    /**
     * ทำเครื่องหมายว่าอ่านแล้ว
     */
    public function markAsRead($id)
    {
        $notification = JobLeadNotificationModel::findOrFail($id);
        $notification->markAsRead();
        
        return response()->json(['success' => true]);
    }
    
    /**
     * ตอบสนองการแจ้งเตือน (รอต่อไป หรือ ถอนใบสมัคร)
     */
    public function respond(Request $request, $id)
    {
        $request->validate([
            'response' => 'required|in:wait,withdraw',
            'note' => 'nullable|string|max:500'
        ]);
        
        try {
            $notification = $this->notificationService->respondToNotification(
                $id,
                $request->response,
                auth()->id(),
                $request->note
            );
            
            $message = $request->response === 'wait' 
                ? 'บันทึกการเลื่อนเวลาเรียบร้อย ระบบจะแจ้งเตือนอีกครั้งในอีก 7 วัน'
                : 'ถอนใบสมัครเรียบร้อยแล้ว';
            
            return response()->json([
                'success' => true,
                'message' => $message
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }
    
    /**
     * แสดงรายละเอียดการแจ้งเตือน
     */
    public function show($id)
    {
        $notification = JobLeadNotificationModel::with(['jobLead.lead', 'jobLead.job', 'respondedBy'])
            ->findOrFail($id);
            
        return view('job-lead-notifications.show', compact('notification'));
    }
}

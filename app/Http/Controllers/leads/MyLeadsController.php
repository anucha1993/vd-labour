<?php

namespace App\Http\Controllers\leads;

use App\Http\Controllers\Controller;
use App\Models\leads\LeadModel;
use App\Models\positions\positionModel;
use Illuminate\Http\Request;

class MyLeadsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display leads where staff is linked to the logged-in user
     */
    public function index(Request $request)
    {
        // Get staff_id that is linked to current user
        $userStaff = \App\Models\staff\staffModel::where('user_id', auth()->id())->first();
        
        if (!$userStaff) {
            // If user is not linked to any staff, show empty result
            $leads = LeadModel::where('staff_id', null)->paginate(20);
            $positions = positionModel::where('position_status', 'active')->get();
            return view('my-leads.index', compact('leads', 'positions'));
        }
        
        $query = LeadModel::with(['position', 'country', 'jobGroup', 'staff', 'recommenderStaff'])
            ->where('staff_id', $userStaff->staff_id);
        
        // Search filters // 
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('lead_firstname', 'like', "%{$search}%")
                  ->orWhere('lead_lastname', 'like', "%{$search}%")
                  ->orWhere('lead_phone', 'like', "%{$search}%")
                  ->orWhere('lead_passport_number', 'like', "%{$search}%")
                  ->orWhereHas('staff', function($q) use ($search) {
                      $q->where('staff_name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('recommenderStaff', function($q) use ($search) {
                      $q->where('staff_sub_name', 'like', "%{$search}%");
                  });
            });
        }
        
        if ($request->filled('lead_status') && $request->lead_status !== 'all') {
            $query->where('lead_status', $request->lead_status);
        }
        
        if ($request->filled('position_id') && $request->position_id !== 'all') {
            $query->where(function($q) use ($request) {
                $q->where('position_id', $request->position_id)
                  ->orWhere('position_id_2', $request->position_id)
                  ->orWhere('position_id_3', $request->position_id);
            });
        }
        
        $leads = $query->latest()->paginate(20);
        
        $positions = positionModel::where('position_status', 'active')->get();
        
        return view('my-leads.index', compact('leads', 'positions'));
    }

    /**
     * Show lead timeline for user's own leads (via staff)
     */
    public function timeline($id)
    {
        // Get staff_id that is linked to current user
        $userStaff = \App\Models\staff\staffModel::where('user_id', auth()->id())->first();
        
        if (!$userStaff) {
            abort(403, 'คุณไม่มีสิทธิ์เข้าถึงข้อมูลนี้');
        }
        
        $lead = LeadModel::where('staff_id', $userStaff->staff_id)
            ->findOrFail($id);
        
        $jobHistories = $lead->jobHistories()
            ->with(['customer', 'position', 'createdBy', 'updatedBy'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        $html = view('leads.partials.timeline', compact('jobHistories', 'lead'))->render();
        
        return response()->json([
            'success' => true,
            'html' => $html
        ]);
    }
}

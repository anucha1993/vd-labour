<?php

namespace App\Http\Controllers\demands;

use App\Http\Controllers\Controller;
use App\Models\demands\DemandModel;
use App\Models\demands\PositionDmModel;
use App\Models\inducstry\inducstryTypeModel;
use App\Models\positions\positionModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DemandController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:view demand')->only(['index', 'show']);
        $this->middleware('permission:create demand')->only(['create', 'store']);
        $this->middleware('permission:update demand')->only(['edit', 'update']);
        $this->middleware('permission:delete demand')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = DemandModel::with(['industryType', 'positions.position', 'createdBy', 'country']);
        
        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('dm_let_no', 'LIKE', "%{$search}%")
                  ->orWhere('dm_com_name', 'LIKE', "%{$search}%")
                  ->orWhere('dm_com_addr', 'LIKE', "%{$search}%")
                  ->orWhere('location', 'LIKE', "%{$search}%");
            });
        }
        
        // Filter by industry type
        if ($request->filled('industry_type')) {
            $query->where('dm_indust_type', $request->industry_type);
        }
        
        // Filter by date range
        if ($request->filled('date_from')) {
            $query->where('dm_issue_date', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->where('dm_issue_date', '<=', $request->date_to);
        }
        
        $demands = $query->orderBy('dm_issue_date', 'desc')->paginate(10);
        
        // Get industry types for filter dropdown
        $industryTypes = \App\Models\inducstry\inducstryTypeModel::all();
        
        return view('demands.index', compact('demands', 'industryTypes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $industryTypes = inducstryTypeModel::all();
        $positions = positionModel::all();
        $countries = \App\Models\country\countryModel::where('country_status', 1)->get();
        return view('demands.create', compact('industryTypes', 'positions', 'countries'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'dm_issue_date' => 'required|date',
            'dm_let_no' => 'required|string|max:255',
            'dm_com_name' => 'required|string|max:255',
            'dm_com_addr' => 'required|string',
            'dm_reg_no' => 'required|string|max:255',
            'dm_indust_type' => 'required|exists:inducstry_type,inducstry_type_id',
            'country_id' => 'required|exists:country,country_id',
            'dm_bmi' => 'nullable|string|max:255',
            'dm_time_work' => 'nullable|string|max:255',
            'dm_sa' => 'nullable|string',
            'dm_job' => 'nullable|string',
            'dm_exp' => 'nullable|array',
            'dm_exp.*' => 'in:accomm,food,med,shuttle',
            'location' => 'nullable|string|max:255',
            'date' => 'nullable|string|max:255',
            'positions' => 'required|array|min:1',
            'positions.*.position_id' => 'required|exists:position,position_id',
            'positions.*.amount' => 'required|numeric|min:0',
            'positions.*.period' => 'required|string|max:255',
            'positions.*.age' => 'required|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            // Create demand
            $demandData = $request->except('positions');
            $demandData['created_by'] = auth()->id();
            $demandData['updated_by'] = auth()->id();
            $demand = DemandModel::create($demandData);
            
            // Create positions
            foreach ($request->positions as $positionData) {
                PositionDmModel::create([
                    'dm_id' => $demand->dm_id,
                    'position_id' => $positionData['position_id'],
                    'position_dm_amount' => $positionData['amount'],
                    'position_dm_period' => $positionData['period'],
                    'position_dm_age' => $positionData['age'],
                ]);
            }
            
            DB::commit();
            return redirect()->route('demands.index')->with('success', 'สร้าง Demand เรียบร้อยแล้ว');
            
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'เกิดข้อผิดพลาด: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $demand = DemandModel::with(['industryType', 'positions.position'])->findOrFail($id);
        return view('demands.show', compact('demand'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $demand = DemandModel::with('positions')->findOrFail($id);
        $industryTypes = inducstryTypeModel::all();
        $positions = positionModel::all();
        $countries = \App\Models\country\countryModel::where('country_status', 1)->get();
        return view('demands.edit', compact('demand', 'industryTypes', 'positions', 'countries'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'dm_issue_date' => 'required|date',
            'dm_let_no' => 'required|string|max:255',
            'dm_com_name' => 'required|string|max:255',
            'dm_com_addr' => 'required|string',
            'dm_reg_no' => 'required|string|max:255',
            'dm_indust_type' => 'required|exists:inducstry_type,inducstry_type_id',
            'country_id' => 'required|exists:country,country_id',
            'dm_bmi' => 'nullable|string|max:255',
            'dm_time_work' => 'nullable|string|max:255',
            'dm_sa' => 'nullable|string',
            'dm_job' => 'nullable|string',
            'dm_exp' => 'nullable|array',
            'dm_exp.*' => 'in:accomm,food,med,shuttle',
            'location' => 'nullable|string|max:255',
            'date' => 'nullable|string|max:255',
            'positions' => 'required|array|min:1',
            'positions.*.position_id' => 'required|exists:position,position_id',
            'positions.*.amount' => 'required|numeric|min:0',
            'positions.*.period' => 'required|string|max:255',
            'positions.*.age' => 'required|string|max:255',
        ]);

        $demand = DemandModel::findOrFail($id);
        
        DB::beginTransaction();
        try {
            // Update demand
            $demandData = $request->except('positions');
            $demandData['updated_by'] = auth()->id();
            $demand->update($demandData);
            
            // Delete old positions
            PositionDmModel::where('dm_id', $demand->dm_id)->delete();
            
            // Create new positions
            foreach ($request->positions as $positionData) {
                PositionDmModel::create([
                    'dm_id' => $demand->dm_id,
                    'position_id' => $positionData['position_id'],
                    'position_dm_amount' => $positionData['amount'],
                    'position_dm_period' => $positionData['period'],
                    'position_dm_age' => $positionData['age'],
                ]);
            }
            
            DB::commit();
            return redirect()->route('demands.index')->with('success', 'แก้ไข Demand เรียบร้อยแล้ว');
            
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'เกิดข้อผิดพลาด: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $demand = DemandModel::findOrFail($id);
        
        DB::beginTransaction();
        try {
            // Delete related positions first
            PositionDmModel::where('dm_id', $demand->dm_id)->delete();
            
            // Delete demand
            $demand->delete();
            
            DB::commit();
            return redirect()->route('demands.index')->with('success', 'ลบ Demand เรียบร้อยแล้ว');
            
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'เกิดข้อผิดพลาด: ' . $e->getMessage());
        }
    }

    /**
     * Generate PDF for demand
     */
    public function generatePdf(string $id)
    {
        return app(\App\Http\Controllers\PdfController::class)->generateDemandPdf($id);
    }

    /**
     * Print demand letter
     */
    public function print(string $id)
    {
        $demand = DemandModel::with(['industryType', 'positions.position'])->findOrFail($id);
        return view('demands.print', compact('demand'));
    }
}

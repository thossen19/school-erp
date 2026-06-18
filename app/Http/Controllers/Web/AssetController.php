<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AssetController extends Controller
{
    private function schoolId() { return 1; }

    public function index(Request $request)
    {
        $sid = $this->schoolId();
        $q = DB::table('assets')->where('assets.school_id', $sid)
            ->join('asset_categories', 'assets.category_id', '=', 'asset_categories.id')
            ->select('assets.*', 'asset_categories.name as category_name');
        if ($search = $request->search) {
            $q->where(function ($sq) use ($search) {
                $sq->where('assets.name', 'like', "%{$search}%")
                   ->orWhere('assets.code', 'like', "%{$search}%")
                   ->orWhere('assets.serial_number', 'like', "%{$search}%")
                   ->orWhere('assets.barcode', 'like', "%{$search}%");
            });
        }
        if ($request->category_id) $q->where('assets.category_id', $request->category_id);
        if ($request->status) $q->where('assets.status', $request->status);
        $assets = $q->orderBy('assets.created_at', 'desc')->paginate(20);
        $categories = DB::table('asset_categories')->where('school_id', $sid)->orderBy('name')->get(['id', 'name']);
        return view('assets.index', compact('assets', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|integer|exists:asset_categories,id', 'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:assets,code', 'description' => 'nullable|string',
            'purchase_date' => 'nullable|date', 'purchase_price' => 'nullable|numeric|min:0',
            'current_value' => 'nullable|numeric|min:0', 'salvage_value' => 'nullable|numeric|min:0',
            'useful_life' => 'nullable|integer|min:1', 'depreciation_method' => 'nullable|string|max:50',
            'depreciation_rate' => 'nullable|numeric|min:0|max:100', 'location' => 'nullable|string|max:255',
            'serial_number' => 'nullable|string|max:255', 'status' => 'nullable|string|max:50',
        ]);
        DB::table('assets')->insert([
            'school_id' => $sid = $this->schoolId(), 'category_id' => $request->category_id, 'name' => $request->name,
            'code' => $request->code, 'description' => $request->description, 'purchase_date' => $request->purchase_date,
            'purchase_price' => $request->purchase_price ?? 0, 'current_value' => $request->current_value ?? 0,
            'salvage_value' => $request->salvage_value ?? 0, 'useful_life' => $request->useful_life,
            'depreciation_method' => $request->depreciation_method, 'depreciation_rate' => $request->depreciation_rate,
            'location' => $request->location, 'serial_number' => $request->serial_number,
            'status' => $request->status ?? 'active', 'barcode' => $this->generateBarcode(),
            'created_at' => now(), 'updated_at' => now(),
        ]);
        return redirect()->route('asset.index')->with('success', 'Asset registered');
    }

    public function update(Request $request, int $id)
    {
        $request->validate([
            'category_id' => 'sometimes|integer|exists:asset_categories,id', 'name' => 'sometimes|string|max:255',
            'code' => 'sometimes|string|max:50|unique:assets,code,'.$id, 'description' => 'nullable|string',
            'purchase_date' => 'nullable|date', 'purchase_price' => 'nullable|numeric|min:0',
            'current_value' => 'nullable|numeric|min:0', 'salvage_value' => 'nullable|numeric|min:0',
            'useful_life' => 'nullable|integer|min:1', 'location' => 'nullable|string|max:255',
            'serial_number' => 'nullable|string|max:255', 'status' => 'nullable|string|max:50',
        ]);
        $upd = [];
        foreach (['category_id','name','code','description','purchase_date','purchase_price','current_value','salvage_value','useful_life','location','serial_number','status'] as $f) {
            if ($request->has($f)) $upd[$f] = $request->$f;
        }
        $upd['updated_at'] = now();
        DB::table('assets')->where('id', $id)->update($upd);
        return redirect()->route('asset.index')->with('success', 'Asset updated');
    }

    public function destroy(int $id)
    {
        DB::table('assets')->where('id', $id)->delete();
        return redirect()->route('asset.index')->with('success', 'Asset deleted');
    }

    private function generateBarcode(): string
    {
        return 'AST-' . strtoupper(substr(uniqid(), -8));
    }

    public function tagging(Request $request)
    {
        $sid = $this->schoolId();
        $q = DB::table('assets')->where('assets.school_id', $sid)
            ->join('asset_categories', 'assets.category_id', '=', 'asset_categories.id')
            ->select('assets.*', 'asset_categories.name as category_name');
        if ($search = $request->search) $q->where(function ($sq) use ($search) {
            $sq->where('assets.name', 'like', "%{$search}%")->orWhere('assets.barcode', 'like', "%{$search}%")->orWhere('assets.code', 'like', "%{$search}%");
        });
        if ($request->tag_status === 'tagged') $q->whereNotNull('assets.barcode');
        if ($request->tag_status === 'untagged') $q->whereNull('assets.barcode');
        $assets = $q->orderBy('assets.name')->paginate(20);
        return view('assets.tagging', compact('assets'));
    }

    public function updateTag(Request $request, int $id)
    {
        $request->validate(['barcode' => 'nullable|string|max:255|unique:assets,barcode,'.$id]);
        DB::table('assets')->where('id', $id)->update([
            'barcode' => $request->barcode ?: $this->generateBarcode(), 'updated_at' => now(),
        ]);
        return redirect()->route('asset.tagging')->with('success', 'Asset tag updated');
    }

    public function barcodeTracking(Request $request)
    {
        $sid = $this->schoolId();
        $q = DB::table('assets')->where('assets.school_id', $sid)->whereNotNull('assets.barcode')
            ->join('asset_categories', 'assets.category_id', '=', 'asset_categories.id')
            ->select('assets.*', 'asset_categories.name as category_name');
        if ($search = $request->search) {
            $q->where(function ($sq) use ($search) {
                $sq->where('assets.barcode', 'like', "%{$search}%")->orWhere('assets.name', 'like', "%{$search}%")->orWhere('assets.code', 'like', "%{$search}%");
            });
        }
        $assets = $q->orderBy('assets.name')->paginate(20);
        return view('assets.barcode-tracking', compact('assets'));
    }

    public function allocations(Request $request)
    {
        $sid = $this->schoolId();
        $q = DB::table('asset_allocations')->where('asset_allocations.school_id', $sid)
            ->join('assets', 'asset_allocations.asset_id', '=', 'assets.id')
            ->join('asset_categories', 'assets.category_id', '=', 'asset_categories.id')
            ->select('asset_allocations.*', 'assets.name as asset_name', 'assets.code as asset_code', 'asset_categories.name as category_name');
        if ($search = $request->search) {
            $q->where(function ($sq) use ($search) {
                $sq->where('assets.name', 'like', "%{$search}%")->orWhere('assets.code', 'like', "%{$search}%");
            });
        }
        $allocations = $q->orderBy('asset_allocations.allocation_date', 'desc')->paginate(20);
        $assets = DB::table('assets')->where('school_id', $sid)->orderBy('name')->get(['id', 'name', 'code']);
        return view('assets.allocations', compact('allocations', 'assets'));
    }

    public function storeAllocation(Request $request)
    {
        $request->validate([
            'asset_id' => 'required|integer|exists:assets,id', 'allocated_to_type' => 'required|string|max:50',
            'allocated_to_id' => 'required|integer', 'allocation_date' => 'required|date',
            'expected_return_date' => 'nullable|date', 'remarks' => 'nullable|string',
        ]);
        DB::table('asset_allocations')->insert([
            'school_id' => $this->schoolId(), 'asset_id' => $request->asset_id,
            'allocated_to_type' => $request->allocated_to_type, 'allocated_to_id' => $request->allocated_to_id,
            'allocation_date' => $request->allocation_date, 'expected_return_date' => $request->expected_return_date,
            'remarks' => $request->remarks, 'created_at' => now(), 'updated_at' => now(),
        ]);
        DB::table('assets')->where('id', $request->asset_id)->update(['is_allocated' => true, 'updated_at' => now()]);
        return redirect()->route('asset.allocations')->with('success', 'Asset allocated');
    }

    public function returnAllocation(Request $request, int $id)
    {
        $request->validate(['actual_return_date' => 'required|date', 'condition_on_return' => 'nullable|string']);
        $allocation = DB::table('asset_allocations')->where('id', $id)->first();
        DB::table('asset_allocations')->where('id', $id)->update([
            'actual_return_date' => $request->actual_return_date, 'condition_on_return' => $request->condition_on_return,
            'updated_at' => now(),
        ]);
        if ($allocation) {
            DB::table('assets')->where('id', $allocation->asset_id)->update(['is_allocated' => false, 'updated_at' => now()]);
        }
        return redirect()->route('asset.allocations')->with('success', 'Asset returned');
    }

    public function deleteAllocation(int $id)
    {
        $allocation = DB::table('asset_allocations')->where('id', $id)->first();
        DB::table('asset_allocations')->where('id', $id)->delete();
        if ($allocation) {
            DB::table('assets')->where('id', $allocation->asset_id)->update(['is_allocated' => false, 'updated_at' => now()]);
        }
        return redirect()->route('asset.allocations')->with('success', 'Allocation deleted');
    }

    public function maintenance(Request $request)
    {
        $sid = $this->schoolId();
        $q = DB::table('asset_maintenances')->where('asset_maintenances.school_id', $sid)
            ->join('assets', 'asset_maintenances.asset_id', '=', 'assets.id')
            ->select('asset_maintenances.*', 'assets.name as asset_name', 'assets.code as asset_code');
        if ($search = $request->search) {
            $q->where(function ($sq) use ($search) {
                $sq->where('assets.name', 'like', "%{$search}%")->orWhere('asset_maintenances.maintenance_type', 'like', "%{$search}%");
            });
        }
        if ($request->status) $q->where('asset_maintenances.status', $request->status);
        $records = $q->orderBy('asset_maintenances.maintenance_date', 'desc')->paginate(20);
        $assets = DB::table('assets')->where('school_id', $sid)->orderBy('name')->get(['id', 'name', 'code']);
        return view('assets.maintenance', compact('records', 'assets'));
    }

    public function storeMaintenance(Request $request)
    {
        $request->validate([
            'asset_id' => 'required|integer|exists:assets,id', 'maintenance_type' => 'required|string|max:100',
            'maintenance_date' => 'required|date', 'description' => 'nullable|string',
            'cost' => 'nullable|numeric|min:0', 'vendor' => 'nullable|string|max:255',
            'next_maintenance_date' => 'nullable|date', 'status' => 'nullable|string|max:50',
        ]);
        DB::table('asset_maintenances')->insert([
            'school_id' => $this->schoolId(), 'asset_id' => $request->asset_id,
            'maintenance_type' => $request->maintenance_type, 'maintenance_date' => $request->maintenance_date,
            'description' => $request->description, 'cost' => $request->cost ?? 0, 'vendor' => $request->vendor,
            'next_maintenance_date' => $request->next_maintenance_date, 'status' => $request->status ?? 'pending',
            'created_at' => now(), 'updated_at' => now(),
        ]);
        return redirect()->route('asset.maintenance')->with('success', 'Maintenance record created');
    }

    public function updateMaintenance(Request $request, int $id)
    {
        $request->validate([
            'maintenance_type' => 'sometimes|string|max:100', 'maintenance_date' => 'sometimes|date',
            'description' => 'nullable|string', 'cost' => 'nullable|numeric|min:0',
            'vendor' => 'nullable|string|max:255', 'next_maintenance_date' => 'nullable|date',
            'status' => 'nullable|string|max:50',
        ]);
        $upd = [];
        foreach (['maintenance_type','maintenance_date','description','cost','vendor','next_maintenance_date','status'] as $f) {
            if ($request->has($f)) $upd[$f] = $request->$f;
        }
        $upd['updated_at'] = now();
        DB::table('asset_maintenances')->where('id', $id)->update($upd);
        return redirect()->route('asset.maintenance')->with('success', 'Maintenance updated');
    }

    public function deleteMaintenance(int $id)
    {
        DB::table('asset_maintenances')->where('id', $id)->delete();
        return redirect()->route('asset.maintenance')->with('success', 'Maintenance deleted');
    }

    public function depreciation(Request $request)
    {
        $sid = $this->schoolId();
        $q = DB::table('asset_depreciations')->where('asset_depreciations.school_id', $sid)
            ->join('assets', 'asset_depreciations.asset_id', '=', 'assets.id')
            ->select('asset_depreciations.*', 'assets.name as asset_name', 'assets.code as asset_code');
        if ($search = $request->search) {
            $q->where(function ($sq) use ($search) {
                $sq->where('assets.name', 'like', "%{$search}%")->orWhere('assets.code', 'like', "%{$search}%");
            });
        }
        $depreciations = $q->orderBy('asset_depreciations.depreciation_date', 'desc')->paginate(20);
        $assets = DB::table('assets')->where('school_id', $sid)->orderBy('name')->get(['id', 'name', 'code', 'current_value', 'purchase_price', 'salvage_value', 'useful_life', 'depreciation_method']);
        return view('assets.depreciation', compact('depreciations', 'assets'));
    }

    public function storeDepreciation(Request $request)
    {
        $request->validate([
            'asset_id' => 'required|integer|exists:assets,id', 'depreciation_date' => 'required|date',
            'amount' => 'required|numeric|min:0',
        ]);
        $asset = DB::table('assets')->where('id', $request->asset_id)->first(['current_value', 'salvage_value']);
        $prevDep = DB::table('asset_depreciations')->where('asset_id', $request->asset_id)->orderBy('id', 'desc')->first();
        $accumulated = ($prevDep->accumulated_depreciation ?? 0) + $request->amount;
        $netBook = $asset->current_value - $request->amount;
        DB::table('asset_depreciations')->insert([
            'school_id' => $this->schoolId(), 'asset_id' => $request->asset_id,
            'depreciation_date' => $request->depreciation_date, 'amount' => $request->amount,
            'accumulated_depreciation' => $accumulated, 'net_book_value' => max($netBook, $asset->salvage_value),
            'created_at' => now(), 'updated_at' => now(),
        ]);
        DB::table('assets')->where('id', $request->asset_id)->update([
            'current_value' => max($netBook, $asset->salvage_value), 'updated_at' => now(),
        ]);
        return redirect()->route('asset.depreciation')->with('success', 'Depreciation recorded');
    }

    public function deleteDepreciation(int $id)
    {
        DB::table('asset_depreciations')->where('id', $id)->delete();
        return redirect()->route('asset.depreciation')->with('success', 'Depreciation deleted');
    }

    public function audit(Request $request)
    {
        $sid = $this->schoolId();
        $q = DB::table('asset_audits')->where('asset_audits.school_id', $sid)
            ->join('assets', 'asset_audits.asset_id', '=', 'assets.id')
            ->leftJoin('users', 'asset_audits.auditor_id', '=', 'users.id')
            ->select('asset_audits.*', 'assets.name as asset_name', 'assets.code as asset_code', 'users.name as auditor_name');
        if ($search = $request->search) {
            $q->where(function ($sq) use ($search) {
                $sq->where('assets.name', 'like', "%{$search}%")->orWhere('assets.code', 'like', "%{$search}%");
            });
        }
        $audits = $q->orderBy('asset_audits.audit_date', 'desc')->paginate(20);
        $assets = DB::table('assets')->where('school_id', $sid)->orderBy('name')->get(['id', 'name', 'code']);
        return view('assets.audit', compact('audits', 'assets'));
    }

    public function storeAudit(Request $request)
    {
        $request->validate([
            'asset_id' => 'required|integer|exists:assets,id', 'audit_date' => 'required|date',
            'condition' => 'nullable|string|max:50', 'location' => 'nullable|string|max:255',
            'remarks' => 'nullable|string', 'physical_condition' => 'nullable|string|max:50',
            'actual_location' => 'nullable|string|max:255', 'is_missing' => 'boolean',
        ]);
        DB::table('asset_audits')->insert([
            'school_id' => $this->schoolId(), 'asset_id' => $request->asset_id, 'audit_date' => $request->audit_date,
            'condition' => $request->condition, 'location' => $request->location,
            'remarks' => $request->remarks, 'physical_condition' => $request->physical_condition,
            'actual_location' => $request->actual_location, 'is_missing' => $request->boolean('is_missing'),
            'created_at' => now(), 'updated_at' => now(),
        ]);
        if ($request->boolean('is_missing')) {
            DB::table('assets')->where('id', $request->asset_id)->update(['status' => 'missing', 'updated_at' => now()]);
        }
        return redirect()->route('asset.audit')->with('success', 'Audit record created');
    }

    public function deleteAudit(int $id)
    {
        DB::table('asset_audits')->where('id', $id)->delete();
        return redirect()->route('asset.audit')->with('success', 'Audit deleted');
    }

    public function reports()
    {
        $sid = $this->schoolId();
        $totalAssets = DB::table('assets')->where('school_id', $sid)->count();
        $totalValue = DB::table('assets')->where('school_id', $sid)->sum('current_value');
        $allocated = DB::table('assets')->where('school_id', $sid)->where('is_allocated', true)->count();
        $pendingMaintenance = DB::table('asset_maintenances')->where('school_id', $sid)->where('status', 'pending')->count();
        $totalDepreciation = DB::table('asset_depreciations')->where('school_id', $sid)->sum('amount');
        $missingAssets = DB::table('assets')->where('school_id', $sid)->where('status', 'missing')->count();

        $statusBreakdown = DB::table('assets')->where('school_id', $sid)->selectRaw("status, count(*) as total")->groupBy('status')->get();
        $categoryBreakdown = DB::table('assets')->where('assets.school_id', $sid)
            ->join('asset_categories', 'assets.category_id', '=', 'asset_categories.id')
            ->selectRaw("asset_categories.name as category_name, count(*) as total, sum(assets.current_value) as total_value")
            ->groupBy('asset_categories.name')->orderByDesc('total')->get();
        $recentAudits = DB::table('asset_audits')->where('asset_audits.school_id', $sid)
            ->join('assets', 'asset_audits.asset_id', '=', 'assets.id')
            ->select('asset_audits.*', 'assets.name as asset_name', 'assets.code as asset_code')
            ->orderBy('asset_audits.audit_date', 'desc')->limit(10)->get();

        return view('assets.reports', compact('totalAssets', 'totalValue', 'allocated', 'pendingMaintenance', 'totalDepreciation', 'missingAssets', 'statusBreakdown', 'categoryBreakdown', 'recentAudits'));
    }
}

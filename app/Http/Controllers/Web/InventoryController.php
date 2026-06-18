<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{
    public function items(Request $request)
    {
        $items = DB::table('items')
            ->leftJoin('item_categories', 'items.category_id', '=', 'item_categories.id')
            ->select('items.*', 'item_categories.name as category_name')
            ->when($request->search, fn($q) => $q->where('items.name', 'like', "%{$request->search}%"))
            ->when($request->category_id, fn($q) => $q->where('items.category_id', $request->category_id))
            ->when($request->low_stock, fn($q) => $q->whereColumn('items.quantity', '<=', 'items.min_quantity'))
            ->orderBy('items.name')
            ->paginate(20);

        $categories = DB::table('item_categories')->orderBy('name')->get();
        return view('inventory.items', compact('items', 'categories'));
    }

    public function createItem()
    {
        $categories = DB::table('item_categories')->orderBy('name')->get();
        return view('inventory.create-item', compact('categories'));
    }

    public function storeItem(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:125',
            'code' => 'required|string|max:50|unique:items,code',
            'category_id' => 'nullable|integer|exists:item_categories,id',
            'description' => 'nullable|string|max:1000',
            'unit' => 'nullable|string|max:50',
            'quantity' => 'required|numeric|min:0',
            'min_quantity' => 'required|numeric|min:0',
            'price' => 'required|numeric|min:0',
        ]);

        $validated['school_id'] = 1;
        DB::table('items')->insert($validated);
        return redirect()->route('inventory.items')->with('success', 'Item created successfully');
    }

    public function showItem(int $id)
    {
        $item = DB::table('items')
            ->leftJoin('item_categories', 'items.category_id', '=', 'item_categories.id')
            ->select('items.*', 'item_categories.name as category_name')
            ->where('items.id', $id)
            ->first();

        if (!$item) abort(404);

        $movements = DB::table('stock_movements')
            ->where('item_id', $id)
            ->orderBy('movement_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('inventory.show-item', compact('item', 'movements'));
    }

    public function editItem(int $id)
    {
        $item = DB::table('items')->where('id', $id)->first();
        if (!$item) abort(404);
        $categories = DB::table('item_categories')->orderBy('name')->get();
        return view('inventory.edit-item', compact('item', 'categories'));
    }

    public function updateItem(Request $request, int $id)
    {
        $item = DB::table('items')->where('id', $id)->first();
        if (!$item) abort(404);

        $validated = $request->validate([
            'name' => 'sometimes|string|max:125',
            'code' => 'sometimes|string|max:50|unique:items,code,' . $id,
            'category_id' => 'nullable|integer|exists:item_categories,id',
            'description' => 'nullable|string|max:1000',
            'unit' => 'nullable|string|max:50',
            'quantity' => 'sometimes|numeric|min:0',
            'min_quantity' => 'sometimes|numeric|min:0',
            'price' => 'sometimes|numeric|min:0',
        ]);

        DB::table('items')->where('id', $id)->update($validated);
        return redirect()->route('inventory.items')->with('success', 'Item updated successfully');
    }

    public function categories(Request $request)
    {
        $categories = DB::table('item_categories')
            ->when($request->search, fn($q) => $q->where('name', 'like', "%{$request->search}%"))
            ->orderBy('name')
            ->paginate(20);

        return view('inventory.categories', compact('categories'));
    }

    public function stock(Request $request)
    {
        $items = DB::table('items')
            ->leftJoin('item_categories', 'items.category_id', '=', 'item_categories.id')
            ->select('items.*', 'item_categories.name as category_name')
            ->when($request->search, fn($q) => $q->where('items.name', 'like', "%{$request->search}%"))
            ->when($request->filter === 'low', fn($q) => $q->whereColumn('items.quantity', '<=', 'items.min_quantity'))
            ->when($request->filter === 'out', fn($q) => $q->where('items.quantity', '<=', 0))
            ->orderBy('items.name')
            ->paginate(20);

        return view('inventory.stock', compact('items'));
    }

    public function vendors(Request $request)
    {
        $vendors = DB::table('vendors')
            ->when($request->search, fn($q) => $q->where('name', 'like', "%{$request->search}%"))
            ->orderBy('name')
            ->paginate(20);

        return view('inventory.vendors', compact('vendors'));
    }

    public function createVendor()
    {
        return view('inventory.create-vendor');
    }

    public function storeVendor(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:125',
            'code' => 'required|string|max:50|unique:vendors,code',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:125',
            'address' => 'nullable|string|max:1000',
            'contact_person' => 'nullable|string|max:125',
            'gst_number' => 'nullable|string|max:50',
        ]);

        $validated['school_id'] = 1;
        $validated['status'] = 1;
        DB::table('vendors')->insert($validated);
        return redirect()->route('inventory.vendors')->with('success', 'Vendor added successfully');
    }

    public function purchaseOrders(Request $request)
    {
        $orders = DB::table('purchase_orders')
            ->leftJoin('vendors', 'purchase_orders.vendor_id', '=', 'vendors.id')
            ->select('purchase_orders.*', 'vendors.name as vendor_name')
            ->when($request->status, fn($q) => $q->where('purchase_orders.status', $request->status))
            ->orderBy('purchase_orders.order_date', 'desc')
            ->paginate(20);

        return view('inventory.purchase-orders', compact('orders'));
    }

    public function stockTransfers(Request $request)
    {
        $transfers = DB::table('stock_movements')
            ->leftJoin('items', 'stock_movements.item_id', '=', 'items.id')
            ->select('stock_movements.*', 'items.name as item_name', 'items.code as item_code')
            ->orderBy('stock_movements.created_at', 'desc')
            ->paginate(20);

        return view('inventory.stock-transfers', compact('transfers'));
    }

    public function stockAudit(Request $request)
    {
        $audits = DB::table('stock_audits')
            ->leftJoin('items', 'stock_audits.item_id', '=', 'items.id')
            ->select('stock_audits.*', 'items.name as item_name', 'items.code as item_code')
            ->orderBy('stock_audits.audit_date', 'desc')
            ->paginate(20);

        return view('inventory.stock-audit', compact('audits'));
    }

    public function barcode(Request $request)
    {
        $items = DB::table('items')
            ->when($request->search, fn($q) => $q->where('name', 'like', "%{$request->search}%"))
            ->orderBy('name')
            ->paginate(20);

        return view('inventory.barcode', compact('items'));
    }

    public function report()
    {
        $totalItems = DB::table('items')->count();
        $totalCategories = DB::table('item_categories')->count();
        $totalVendors = DB::table('vendors')->count();
        $totalStock = DB::table('items')->sum('quantity');
        $lowStockItems = DB::table('items')->whereColumn('quantity', '<=', 'min_quantity')->count();
        $outOfStock = DB::table('items')->where('quantity', '<=', 0)->count();
        $totalOrders = DB::table('purchase_orders')->count();
        $pendingOrders = DB::table('purchase_orders')->where('status', 'pending')->count();
        $totalMovements = DB::table('stock_movements')->count();
        $totalAudits = DB::table('stock_audits')->count();

        return view('inventory.report', compact(
            'totalItems', 'totalCategories', 'totalVendors', 'totalStock',
            'lowStockItems', 'outOfStock', 'totalOrders', 'pendingOrders',
            'totalMovements', 'totalAudits'
        ));
    }
}

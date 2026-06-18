<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Inventory\PurchaseOrder;
use App\Services\Inventory\PurchaseService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PurchaseOrderController extends Controller
{
    use ApiResponseTrait;

    protected PurchaseService $purchaseService;

    public function __construct(PurchaseService $purchaseService)
    {
        $this->purchaseService = $purchaseService;
    }

    public function index(Request $request): JsonResponse
    {
        $orders = PurchaseOrder::with('vendor', 'items.item')->when($request->vendor_id, fn($q) => $q->where('vendor_id', $request->vendor_id))->when($request->status, fn($q) => $q->where('status', $request->status))->when($request->date_from, fn($q) => $q->whereDate('order_date', '>=', $request->date_from))->when($request->date_to, fn($q) => $q->whereDate('order_date', '<=', $request->date_to))->orderBy('order_date', 'desc')->paginate($request->per_page ?? 15);

        return $this->paginatedResponse($orders, 'Purchase orders retrieved');
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'vendor_id' => 'required|integer|exists:vendors,id',
            'order_no' => 'required|string|max:50|unique:purchase_orders,order_no',
            'order_date' => 'required|date',
            'expected_delivery_date' => 'nullable|date|after_or_equal:order_date',
            'notes' => 'nullable|string|max:1000',
            'items' => 'required|array|min:1',
            'items.*.item_id' => 'required|integer|exists:items,id',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.discount' => 'nullable|numeric|min:0',
        ]);

        $order = $this->purchaseService->createPurchaseOrder($request->validated());
        return $this->createdResponse($order->load('vendor', 'items.item'), 'Purchase order created');
    }

    public function show(int $id): JsonResponse
    {
        return $this->successResponse(
            PurchaseOrder::with('vendor', 'items.item')->findOrFail($id),
            'Purchase order retrieved'
        );
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $order = PurchaseOrder::findOrFail($id);
        $validated = $request->validate([
            'expected_delivery_date' => 'nullable|date',
            'notes' => 'nullable|string|max:1000',
            'status' => 'nullable|string|in:pending,approved,cancelled',
        ]);
        $order->update($validated);
        return $this->updatedResponse($order->fresh()->load('vendor', 'items.item'), 'Purchase order updated');
    }

    public function destroy(int $id): JsonResponse
    {
        PurchaseOrder::findOrFail($id)->delete();
        return $this->deletedResponse('Purchase order deleted');
    }

    public function receiveOrder(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'received_items' => 'required|array|min:1',
            'received_items.*.purchase_order_item_id' => 'required|integer|exists:purchase_order_items,id',
            'received_items.*.received_quantity' => 'required|numeric|min:0',
            'received_items.*.defective_quantity' => 'nullable|numeric|min:0',
            'received_date' => 'required|date',
            'notes' => 'nullable|string|max:500',
        ]);

        $order = $this->purchaseService->receiveOrder($id, $request->validated());
        return $this->successResponse($order->load('vendor', 'items.item'), 'Order received');
    }
}

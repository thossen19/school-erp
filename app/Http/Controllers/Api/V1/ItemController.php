<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Inventory\StoreItemRequest;
use App\Models\Inventory\Item;
use App\Services\Inventory\InventoryService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    use ApiResponseTrait;

    protected InventoryService $inventoryService;

    public function __construct(InventoryService $inventoryService)
    {
        $this->inventoryService = $inventoryService;
    }

    public function index(Request $request): JsonResponse
    {
        $items = Item::with('category')->when($request->category_id, fn($q) => $q->where('category_id', $request->category_id))->when($request->status, fn($q) => $q->where('status', $request->status))->when($request->search, fn($q) => $q->where('name', 'like', "%{$request->search}%")->orWhere('code', 'like', "%{$request->search}%"))->orderBy('name')->paginate($request->per_page ?? 15);

        return $this->paginatedResponse($items, 'Items retrieved');
    }

    public function store(StoreItemRequest $request): JsonResponse
    {
        $item = Item::create($request->validated());
        return $this->createdResponse($item->load('category'), 'Item created');
    }

    public function show(int $id): JsonResponse
    {
        return $this->successResponse(
            Item::with('category', 'stockMovements', 'purchaseOrderItems')->findOrFail($id),
            'Item retrieved'
        );
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $item = Item::findOrFail($id);
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'code' => 'sometimes|string|max:50|unique:items,code,' . $id,
            'category_id' => 'sometimes|integer|exists:item_categories,id',
            'unit' => 'nullable|string|max:50',
            'unit_price' => 'nullable|numeric|min:0',
            'quantity' => 'sometimes|numeric|min:0',
            'min_quantity' => 'nullable|numeric|min:0',
            'max_quantity' => 'nullable|numeric|min:0',
            'location' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'status' => 'nullable|string|in:available,out_of_stock,discontinued',
        ]);
        $item->update($validated);
        return $this->updatedResponse($item->fresh()->load('category'), 'Item updated');
    }

    public function destroy(int $id): JsonResponse
    {
        Item::findOrFail($id)->delete();
        return $this->deletedResponse('Item deleted');
    }

    public function manageStock(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'type' => 'required|string|in:add,remove',
            'quantity' => 'required|numeric|min:0.01',
            'remarks' => 'nullable|string|max:500',
        ]);

        $item = Item::findOrFail($id);

        if ($request->type === 'add') {
            $item->increment('quantity', $request->quantity);
        } else {
            if ($item->quantity < $request->quantity) {
                return $this->errorResponse('Insufficient stock', 400);
            }
            $item->decrement('quantity', $request->quantity);
        }

        $item->stockMovements()->create([
            'type' => $request->type,
            'quantity' => $request->quantity,
            'balance_quantity' => $item->quantity,
            'remarks' => $request->remarks,
            'user_id' => $request->user()->id,
        ]);

        return $this->successResponse($item->fresh()->load('category'), 'Stock updated');
    }
}

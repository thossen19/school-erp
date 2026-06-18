<?php

namespace App\Services\Inventory;

use App\Contracts\RepositoryInterface;
use App\Models\Inventory\Item;
use App\Repositories\Inventory\ItemRepository;
use App\Repositories\Inventory\StockMovementRepository;
use App\Repositories\Inventory\PurchaseOrderRepository;
use App\Repositories\Inventory\VendorRepository;
use App\Services\BaseService;
use Illuminate\Support\Facades\DB;

class InventoryService extends BaseService
{
    protected ItemRepository $itemRepository;
    protected StockMovementRepository $stockMovementRepository;
    protected PurchaseOrderRepository $purchaseOrderRepository;
    protected VendorRepository $vendorRepository;

    public function __construct(
        ItemRepository $itemRepository,
        StockMovementRepository $stockMovementRepository,
        PurchaseOrderRepository $purchaseOrderRepository,
        VendorRepository $vendorRepository
    ) {
        parent::__construct();
        $this->itemRepository = $itemRepository;
        $this->stockMovementRepository = $stockMovementRepository;
        $this->purchaseOrderRepository = $purchaseOrderRepository;
        $this->vendorRepository = $vendorRepository;
    }

    public function repository(): RepositoryInterface
    {
        return $this->itemRepository;
    }

    public function createItem(array $data): Item
    {
        return DB::transaction(function () use ($data) {
            $data['is_active'] = $data['is_active'] ?? true;
            $item = $this->itemRepository->create($data);
            $this->logActivity('inventory_item_created', $item);
            return $item;
        });
    }

    public function manageStock(int $itemId, int $quantity, string $type = 'add'): Item
    {
        return DB::transaction(function () use ($itemId, $quantity, $type) {
            $item = $this->itemRepository->getById($itemId);
            $adjustment = $type === 'add' ? $quantity : -$quantity;

            $this->itemRepository->adjustStock($itemId, $adjustment);

            $this->stockMovementRepository->recordMovement([
                'item_id' => $itemId,
                'movement_type' => $type === 'add' ? 'in' : 'out',
                'quantity' => abs($quantity),
                'movement_date' => now(),
                'reference_type' => 'manual',
                'notes' => "Manual stock {$type}",
            ]);

            $item = $item->fresh();

            $this->logActivity('stock_managed', [
                'item_id' => $itemId,
                'quantity' => $quantity,
                'type' => $type,
                'new_stock' => $item->stock_quantity,
            ]);

            return $item;
        });
    }

    public function createPurchaseOrder(array $data): \App\Models\Inventory\PurchaseOrder
    {
        return DB::transaction(function () use ($data) {
            if (!isset($data['order_no'])) {
                $data['order_no'] = 'PO-' . now()->format('YmdHis') . '-' . str_pad(mt_rand(1, 999), 3, '0', STR_PAD_LEFT);
            }
            $data['order_date'] = $data['order_date'] ?? now();
            $data['status'] = 'pending';

            $order = $this->purchaseOrderRepository->create($data);

            if (isset($data['items']) && is_array($data['items'])) {
                foreach ($data['items'] as $orderItem) {
                    $orderItem['purchase_order_id'] = $order->id;
                    \App\Models\Inventory\PurchaseOrderItem::create($orderItem);
                }
            }

            $this->logActivity('purchase_order_created', $order);

            return $order;
        });
    }

    public function processStockMovement(array $data): \App\Models\Inventory\StockMovement
    {
        return DB::transaction(function () use ($data) {
            $item = $this->itemRepository->getById($data['item_id']);
            $adjustment = $data['movement_type'] === 'in' ? $data['quantity'] : -$data['quantity'];

            $this->itemRepository->adjustStock($data['item_id'], $adjustment);

            $movement = $this->stockMovementRepository->recordMovement($data);

            $this->logActivity('stock_movement_processed', $movement);

            return $movement;
        });
    }

    public function conductAudit(int $itemId, float $physicalQuantity, ?string $notes = null): array
    {
        return DB::transaction(function () use ($itemId, $physicalQuantity, $notes) {
            $item = $this->itemRepository->getById($itemId);
            $systemQuantity = $item->stock_quantity;
            $difference = $physicalQuantity - $systemQuantity;

            $audit = [
                'item_id' => $itemId,
                'item_name' => $item->name,
                'system_quantity' => $systemQuantity,
                'physical_quantity' => $physicalQuantity,
                'difference' => $difference,
                'notes' => $notes,
                'audited_by' => auth()->id(),
                'audited_at' => now(),
            ];

            if ($difference != 0) {
                $this->itemRepository->adjustStock($itemId, $difference);

                $this->stockMovementRepository->recordMovement([
                    'item_id' => $itemId,
                    'movement_type' => $difference > 0 ? 'in' : 'out',
                    'quantity' => abs($difference),
                    'movement_date' => now(),
                    'reference_type' => 'audit',
                    'notes' => "Audit adjustment: {$notes}",
                ]);
            }

            $this->logActivity('inventory_audit_conducted', $audit);

            return $audit;
        });
    }

    public function generateBarcode(int $itemId): string
    {
        $item = $this->itemRepository->getById($itemId);
        $barcode = $item->code ?? ('ITEM' . str_pad($itemId, 8, '0', STR_PAD_LEFT));

        $this->itemRepository->update($itemId, ['barcode' => $barcode]);

        $this->logActivity('barcode_generated', ['item_id' => $itemId, 'barcode' => $barcode]);

        return $barcode;
    }
}

<?php

namespace App\Services\Inventory;

use App\Contracts\RepositoryInterface;
use App\Exceptions\ServiceException;
use App\Models\Inventory\PurchaseOrder;
use App\Repositories\Inventory\PurchaseOrderRepository;
use App\Repositories\Inventory\VendorRepository;
use App\Repositories\Inventory\ItemRepository;
use App\Repositories\Inventory\StockMovementRepository;
use App\Services\BaseService;
use Illuminate\Support\Facades\DB;

class PurchaseService extends BaseService
{
    protected PurchaseOrderRepository $purchaseOrderRepository;
    protected VendorRepository $vendorRepository;
    protected ItemRepository $itemRepository;
    protected StockMovementRepository $stockMovementRepository;

    public function __construct(
        PurchaseOrderRepository $purchaseOrderRepository,
        VendorRepository $vendorRepository,
        ItemRepository $itemRepository,
        StockMovementRepository $stockMovementRepository
    ) {
        parent::__construct();
        $this->purchaseOrderRepository = $purchaseOrderRepository;
        $this->vendorRepository = $vendorRepository;
        $this->itemRepository = $itemRepository;
        $this->stockMovementRepository = $stockMovementRepository;
    }

    public function repository(): RepositoryInterface
    {
        return $this->purchaseOrderRepository;
    }

    public function createOrder(array $data): PurchaseOrder
    {
        return DB::transaction(function () use ($data) {
            if (!isset($data['order_no'])) {
                $data['order_no'] = 'PO-' . now()->format('YmdHis') . '-' . str_pad(mt_rand(1, 999), 3, '0', STR_PAD_LEFT);
            }
            $data['order_date'] = $data['order_date'] ?? now();
            $data['status'] = 'pending';

            $order = $this->purchaseOrderRepository->create($data);

            if (isset($data['items']) && is_array($data['items'])) {
                foreach ($data['items'] as $item) {
                    $item['purchase_order_id'] = $order->id;
                    \App\Models\Inventory\PurchaseOrderItem::create($item);
                }
            }

            $this->logActivity('purchase_order_created', $order);

            return $order;
        });
    }

    public function receiveOrder(int $orderId, array $receivedItems = []): PurchaseOrder
    {
        return DB::transaction(function () use ($orderId, $receivedItems) {
            $order = $this->purchaseOrderRepository->getById($orderId);

            if ($order->status === 'received') {
                throw new ServiceException("Order has already been received.");
            }

            foreach ($receivedItems as $received) {
                $item = $this->itemRepository->getById($received['item_id']);
                $this->itemRepository->adjustStock($received['item_id'], $received['quantity']);

                $this->stockMovementRepository->recordMovement([
                    'item_id' => $received['item_id'],
                    'movement_type' => 'in',
                    'quantity' => $received['quantity'],
                    'movement_date' => now(),
                    'reference_type' => 'purchase_order',
                    'reference_id' => $orderId,
                    'notes' => "Received from PO {$order->order_no}",
                ]);
            }

            $this->purchaseOrderRepository->receiveOrder($orderId);
            $order = $order->fresh();

            $this->logActivity('purchase_order_received', $order);

            return $order;
        });
    }

    public function manageVendor(array $data): \App\Models\Inventory\Vendor
    {
        return DB::transaction(function () use ($data) {
            $vendor = $this->vendorRepository->create($data);
            $this->logActivity('vendor_managed', $vendor);
            return $vendor;
        });
    }

    public function getPurchaseReport(string $startDate, string $endDate): array
    {
        $orders = $this->purchaseOrderRepository->getByDateRange($startDate, $endDate);

        $report = [
            'period' => ['from' => $startDate, 'to' => $endDate],
            'total_orders' => $orders->count(),
            'total_amount' => $orders->sum('total_amount'),
            'pending' => $orders->where('status', 'pending')->count(),
            'approved' => $orders->where('status', 'approved')->count(),
            'received' => $orders->where('status', 'received')->count(),
            'cancelled' => $orders->where('status', 'cancelled')->count(),
        ];

        $this->logActivity('purchase_report_viewed', $report);

        return $report;
    }
}

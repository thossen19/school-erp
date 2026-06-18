<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Inventory\StockAudit;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StockAuditController extends Controller
{
    use ApiResponseTrait;

    public function index(Request $request): JsonResponse
    {
        $audits = StockAudit::with('item', 'auditedBy')->when($request->item_id, fn($q) => $q->where('item_id', $request->item_id))->when($request->date_from, fn($q) => $q->whereDate('audit_date', '>=', $request->date_from))->when($request->date_to, fn($q) => $q->whereDate('audit_date', '<=', $request->date_to))->when($request->discrepancy, fn($q) => $q->where('has_discrepancy', $request->boolean('discrepancy')))->orderBy('audit_date', 'desc')->paginate($request->per_page ?? 15);

        return $this->paginatedResponse($audits, 'Stock audits retrieved');
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'item_id' => 'required|integer|exists:items,id',
            'audit_date' => 'required|date',
            'system_quantity' => 'required|numeric|min:0',
            'physical_quantity' => 'required|numeric|min:0',
            'difference' => 'required|numeric',
            'has_discrepancy' => 'boolean',
            'remarks' => 'nullable|string|max:1000',
            'action_taken' => 'nullable|string|max:1000',
        ]);

        $audit = StockAudit::create(array_merge($validated, ['audited_by' => $request->user()->id]));
        return $this->createdResponse($audit->load('item', 'auditedBy'), 'Stock audit recorded');
    }

    public function show(int $id): JsonResponse
    {
        return $this->successResponse(StockAudit::with('item', 'auditedBy')->findOrFail($id), 'Stock audit retrieved');
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $audit = StockAudit::findOrFail($id);
        $validated = $request->validate([
            'physical_quantity' => 'sometimes|numeric|min:0',
            'difference' => 'sometimes|numeric',
            'has_discrepancy' => 'boolean',
            'remarks' => 'nullable|string|max:1000',
            'action_taken' => 'nullable|string|max:1000',
        ]);
        $audit->update($validated);
        return $this->updatedResponse($audit->fresh()->load('item'), 'Stock audit updated');
    }

    public function destroy(int $id): JsonResponse
    {
        StockAudit::findOrFail($id)->delete();
        return $this->deletedResponse('Stock audit deleted');
    }
}

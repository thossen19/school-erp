<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Accounting\ChartOfAccount;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ChartOfAccountController extends Controller
{
    use ApiResponseTrait;

    public function index(Request $request): JsonResponse
    {
        $accounts = ChartOfAccount::with('parent')->when($request->type, fn($q) => $q->where('type', $request->type))->when($request->category, fn($q) => $q->where('category', $request->category))->when($request->is_active, fn($q) => $q->where('is_active', $request->boolean('is_active')))->when($request->search, fn($q) => $q->where('name', 'like', "%{$request->search}%")->orWhere('code', 'like', "%{$request->search}%"))->orderBy('code')->paginate($request->per_page ?? 15);

        return $this->paginatedResponse($accounts, 'Chart of accounts retrieved');
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:chart_of_accounts,code',
            'type' => 'required|string|in:asset,liability,equity,income,expense',
            'category' => 'nullable|string|max:100',
            'parent_id' => 'nullable|integer|exists:chart_of_accounts,id',
            'description' => 'nullable|string|max:500',
            'opening_balance' => 'nullable|numeric',
            'current_balance' => 'nullable|numeric',
            'is_active' => 'boolean',
        ]);

        $account = ChartOfAccount::create($validated);
        return $this->createdResponse($account->load('parent'), 'Account created');
    }

    public function show(int $id): JsonResponse
    {
        return $this->successResponse(
            ChartOfAccount::with('parent', 'children', 'journalItems')->findOrFail($id),
            'Account retrieved'
        );
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $account = ChartOfAccount::findOrFail($id);
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'code' => 'sometimes|string|max:50|unique:chart_of_accounts,code,' . $id,
            'category' => 'nullable|string|max:100',
            'parent_id' => 'nullable|integer|exists:chart_of_accounts,id',
            'description' => 'nullable|string|max:500',
            'is_active' => 'boolean',
        ]);
        $account->update($validated);
        return $this->updatedResponse($account->fresh()->load('parent'), 'Account updated');
    }

    public function destroy(int $id): JsonResponse
    {
        ChartOfAccount::findOrFail($id)->delete();
        return $this->deletedResponse('Account deleted');
    }
}

<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Alumni\AlumniDonation;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AlumniDonationController extends Controller
{
    use ApiResponseTrait;

    public function index(Request $request): JsonResponse
    {
        $donations = AlumniDonation::with('alumnus:id,first_name,last_name,batch_year')->when($request->alumni_id, fn($q) => $q->where('alumni_id', $request->alumni_id))->when($request->purpose, fn($q) => $q->where('purpose', $request->purpose))->when($request->date_from, fn($q) => $q->whereDate('donation_date', '>=', $request->date_from))->when($request->date_to, fn($q) => $q->whereDate('donation_date', '<=', $request->date_to))->orderBy('donation_date', 'desc')->paginate($request->per_page ?? 15);

        return $this->paginatedResponse($donations, 'Donations retrieved');
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'alumni_id' => 'required|integer|exists:alumni,id',
            'amount' => 'required|numeric|min:0',
            'currency' => 'nullable|string|max:3|default:USD',
            'purpose' => 'required|string|in:general,school_development,scholarship,infrastructure,event,other',
            'payment_method' => 'nullable|string|in:cash,cheque,online_transfer,credit_card,other',
            'transaction_id' => 'nullable|string|max:100',
            'donation_date' => 'required|date',
            'is_anonymous' => 'boolean',
            'message' => 'nullable|string|max:1000',
            'receipt_required' => 'boolean',
        ]);

        $donation = AlumniDonation::create($validated);
        return $this->createdResponse($donation->load('alumnus:id,first_name,last_name'), 'Donation recorded');
    }

    public function show(int $id): JsonResponse
    {
        return $this->successResponse(AlumniDonation::with('alumnus')->findOrFail($id), 'Donation retrieved');
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $donation = AlumniDonation::findOrFail($id);
        $validated = $request->validate([
            'amount' => 'sometimes|numeric|min:0',
            'purpose' => 'sometimes|string|in:general,school_development,scholarship,infrastructure,event,other',
            'payment_method' => 'nullable|string|in:cash,cheque,online_transfer,credit_card,other',
            'transaction_id' => 'nullable|string|max:100',
        ]);
        $donation->update($validated);
        return $this->updatedResponse($donation->fresh()->load('alumnus'), 'Donation updated');
    }

    public function destroy(int $id): JsonResponse
    {
        AlumniDonation::findOrFail($id)->delete();
        return $this->deletedResponse('Donation deleted');
    }
}

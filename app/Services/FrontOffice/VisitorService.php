<?php

namespace App\Services\FrontOffice;

use App\Contracts\RepositoryInterface;
use App\Models\FrontOffice\Visitor;
use App\Repositories\FrontOffice\VisitorRepository;
use App\Services\BaseService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class VisitorService extends BaseService
{
    protected VisitorRepository $visitorRepository;

    public function __construct(VisitorRepository $visitorRepository)
    {
        parent::__construct();
        $this->visitorRepository = $visitorRepository;
    }

    public function repository(): RepositoryInterface
    {
        return $this->visitorRepository;
    }

    public function checkIn(array $data): Visitor
    {
        return DB::transaction(function () use ($data) {
            $data['visit_date'] = $data['visit_date'] ?? now();
            $data['check_in'] = $data['check_in'] ?? now();
            $data['status'] = 'checked_in';

            $visitor = $this->visitorRepository->create($data);

            $this->logActivity('visitor_checked_in', $visitor);

            return $visitor;
        });
    }

    public function checkOut(int $visitorId): Visitor
    {
        return DB::transaction(function () use ($visitorId) {
            $visitor = $this->visitorRepository->getById($visitorId);

            if ($visitor->status === 'departed') {
                throw new \App\Exceptions\ServiceException("Visitor has already checked out.");
            }

            $this->visitorRepository->markCheckout($visitorId);
            $visitor = $visitor->fresh();

            $this->logActivity('visitor_checked_out', $visitor);

            return $visitor;
        });
    }

    public function getVisitorLog(string $startDate, string $endDate): Collection
    {
        return $this->visitorRepository->getByDateRange($startDate, $endDate);
    }
}

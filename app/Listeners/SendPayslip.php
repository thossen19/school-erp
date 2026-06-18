<?php

namespace App\Listeners;

use App\Events\EmployeePayrollProcessed;
use Illuminate\Support\Facades\Log;

class SendPayslip
{
    public function handle(EmployeePayrollProcessed $event): void
    {
        Log::info("Payslip sent for payroll: {$event->payroll->id}");
    }
}

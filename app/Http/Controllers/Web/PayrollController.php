<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Hr\Employee;
use App\Models\Payroll\Payroll;
use App\Models\Payroll\PayrollBonus;
use App\Models\Payroll\PayrollTax;
use App\Models\Payroll\SalaryComponent;
use App\Models\Payroll\SalaryStructure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PayrollController extends Controller
{
    public function salaryStructures(Request $request)
    {
        $structures = DB::table('salary_structures')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('payroll.salary-structures', compact('structures'));
    }

    public function processing(Request $request)
    {
        $payrolls = DB::table('payrolls')
            ->leftJoin('employees', 'payrolls.employee_id', '=', 'employees.id')
            ->select('payrolls.*', 'employees.first_name', 'employees.last_name', 'employees.employee_no')
            ->orderBy('payrolls.created_at', 'desc')
            ->paginate(15);

        return view('payroll.processing', compact('payrolls'));
    }

    public function loans(Request $request)
    {
        $loans = DB::table('loan_requests')
            ->leftJoin('employees', 'loan_requests.employee_id', '=', 'employees.id')
            ->select('loan_requests.*', 'employees.first_name', 'employees.last_name', 'employees.employee_no')
            ->orderBy('loan_requests.created_at', 'desc')
            ->paginate(15);

        return view('payroll.loans', compact('loans'));
    }

    public function overtime(Request $request)
    {
        $records = DB::table('overtime_records')
            ->leftJoin('employees', 'overtime_records.employee_id', '=', 'employees.id')
            ->select('overtime_records.*', 'employees.first_name', 'employees.last_name', 'employees.employee_no')
            ->orderBy('overtime_records.created_at', 'desc')
            ->paginate(15);

        return view('payroll.overtime', compact('records'));
    }

    public function salaryComponents(Request $request)
    {
        $query = SalaryComponent::with('salaryStructure');

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('search')) {
            $query->where('name', 'like', "%{$request->search}%");
        }

        $components = $query->orderBy('type')->orderBy('name')->paginate(20);
        return view('payroll.salary-components', compact('components'));
    }

    public function taxManagement(Request $request)
    {
        $query = PayrollTax::query();

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('search')) {
            $query->where('name', 'like', "%{$request->search}%");
        }

        $taxes = $query->orderBy('name')->paginate(20);
        return view('payroll.tax-management', compact('taxes'));
    }

    public function bonusManagement(Request $request)
    {
        $query = PayrollBonus::with('employee');

        if ($request->filled('bonus_type')) {
            $query->where('bonus_type', $request->bonus_type);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $query->whereHas('employee', fn($q) => $q->where('first_name', 'like', "%{$request->search}%")
                ->orWhere('last_name', 'like', "%{$request->search}%"));
        }

        $bonuses = $query->orderBy('bonus_date', 'desc')->paginate(20);
        $types = PayrollBonus::select('bonus_type')->distinct()->pluck('bonus_type');
        return view('payroll.bonus-management', compact('bonuses', 'types'));
    }

    public function payrollReports()
    {
        $totalPayrolls = Payroll::count();
        $totalPaid = Payroll::where('status', 'paid')->sum('net_salary');
        $totalPending = Payroll::where('status', 'pending')->sum('net_salary');
        $avgSalary = round(Payroll::avg('net_salary'), 2);

        $byMonth = Payroll::select(DB::raw('year'), DB::raw('month'), DB::raw('count(*) as total'),
            DB::raw('sum(net_salary) as total_amount'), DB::raw('sum(tax_amount) as total_tax'),
            DB::raw('sum(bonus_amount) as total_bonus'))
            ->where('status', 'paid')
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')->orderBy('month', 'desc')
            ->take(12)
            ->get();

        $byStatus = Payroll::select('status', DB::raw('count(*) as total'), DB::raw('sum(net_salary) as amount'))
            ->groupBy('status')->get();

        $currentYear = now()->year;
        $yearlyTotal = Payroll::where('year', $currentYear)->where('status', 'paid')->sum('net_salary');
        $yearlyTax = Payroll::where('year', $currentYear)->where('status', 'paid')->sum('tax_amount');
        $yearlyBonus = Payroll::where('year', $currentYear)->where('status', 'paid')->sum('bonus_amount');

        return view('payroll.payroll-reports', compact(
            'totalPayrolls', 'totalPaid', 'totalPending', 'avgSalary',
            'byMonth', 'byStatus', 'currentYear', 'yearlyTotal', 'yearlyTax', 'yearlyBonus'
        ));
    }

    public function tallyExport(Request $request)
    {
        $query = Payroll::with('employee')
            ->where('status', 'paid');

        if ($request->filled('year')) {
            $query->where('year', $request->year);
        }
        if ($request->filled('month')) {
            $query->where('month', $request->month);
        }

        $payrolls = $query->orderBy('year', 'desc')->orderBy('month', 'desc')->paginate(20);
        $years = Payroll::select('year')->distinct()->orderBy('year', 'desc')->pluck('year');

        return view('payroll.tally-export', compact('payrolls', 'years'));
    }
}

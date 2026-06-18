<?php

namespace App\Http\Middleware;

use App\Models\AcademicYear;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AcademicYearMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $academicYearId = $this->resolveAcademicYear($request);

        if ($academicYearId) {
            $request->merge(['academic_year_id' => $academicYearId]);
            session(['academic_year_id' => $academicYearId]);
        }

        return $next($request);
    }

    protected function resolveAcademicYear(Request $request): ?int
    {
        if ($request->route('academicYear')) {
            return (int) $request->route('academicYear');
        }

        if ($request->route('academic_year_id')) {
            return (int) $request->route('academic_year_id');
        }

        if ($request->has('academic_year_id')) {
            return (int) $request->input('academic_year_id');
        }

        if ($request->header('X-Academic-Year-Id')) {
            return (int) $request->header('X-Academic-Year-Id');
        }

        if (session()->has('academic_year_id')) {
            return (int) session('academic_year_id');
        }

        return $this->getDefaultAcademicYear();
    }

    protected function getDefaultAcademicYear(): ?int
    {
        try {
            $academicYear = AcademicYear::where('is_current', true)->first();

            return $academicYear->id;
        } catch (\Exception) {
            return null;
        }
    }
}

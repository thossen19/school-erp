<?php

namespace App\Http\Middleware;

use App\Exceptions\ServiceException;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SchoolMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $schoolId = $this->resolveSchoolId($request);

        if ($schoolId === null) {
            $user = Auth::user();
            if ($user && $user->user_type === 'super_admin') {
                return $next($request);
            }
            throw new ServiceException('School context is required.', 400);
        }

        $request->merge(['school_id' => $schoolId]);
        session(['school_id' => $schoolId]);

        if (Auth::check()) {
            $user = Auth::user();
            if (method_exists($user, 'setSchoolId')) {
                $user->setSchoolId($schoolId);
            }
        }

        return $next($request);
    }

    protected function resolveSchoolId(Request $request): ?int
    {
        if ($request->route('school')) {
            return (int) $request->route('school');
        }

        if ($request->route('schoolId')) {
            return (int) $request->route('schoolId');
        }

        if ($request->has('school_id')) {
            return (int) $request->input('school_id');
        }

        if ($request->header('X-School-Id')) {
            return (int) $request->header('X-School-Id');
        }

        if (Auth::hasUser()) {
            $user = Auth::user();
            if (isset($user->school_id)) {
                return (int) $user->school_id;
            }
        }

        return null;
    }
}

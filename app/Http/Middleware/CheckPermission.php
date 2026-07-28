<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class CheckPermission
{
    public function handle(Request $request, Closure $next, string ...$permissions): Response
    {
        $user = $request->user();

        if ($user === null) {
            return redirect()->route('login');
        }

        foreach ($permissions as $permission) {
            if (! $user->can($permission)) {
                abort(403, 'ليس لديك صلاحية للوصول إلى هذه الصفحة.');
            }
        }

        return $next($request);
    }
}

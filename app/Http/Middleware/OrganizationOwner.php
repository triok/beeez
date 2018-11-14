<?php

namespace App\Http\Middleware;

use App\Models\OrganizationUsers;
use Closure;

class OrganizationOwner
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->organization->user_id == auth()->id()) {
            return $next($request);
        }

        $connection = OrganizationUsers::where('organization_id', $request->organization->id)
            ->where('user_id', auth()->id())
            ->where('is_admin', true)
            ->first();

        if ($connection && $connection->is_admin) {
            return $next($request);
        }

        flash()->error('Access denied!');

        return redirect(route('organizations.index'));
    }
}

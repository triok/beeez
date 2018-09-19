<?php

namespace App\Http\Middleware;

use Closure;

class OrganizationAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (auth()->user()->email != config('organization.admin')) {
            flash()->error('Access denied!');

            return redirect(route('organizations.index'));
        }

        return $next($request);
    }
}

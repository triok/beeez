<?php

namespace App\Http\Middleware;

use Closure;

class IsAdmin
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
        if (auth()->user()->email != 'admin@app.com') {
            flash()->error('Access denied!');

            return redirect(route('projects.index'));
        }

        return $next($request);
    }
}

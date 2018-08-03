<?php

namespace App\Http\Middleware;

use Closure;

class ProjectOwner
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
        if (isset($request->project) & $request->project->user_id != auth()->user()->id) {
            flash()->error('Access denied!');

            return redirect(route('projects.index'));
        }

        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use App\Models\ProjectUsers;
use App\Models\Structure;
use Closure;
use Illuminate\Support\Facades\Auth;

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
        if (isset($request->project)) {
            if($request->project->user_id == auth()->user()->id) {
                return $next($request);
            }

            if($request->project->structure_id) {
                $structure = Structure::find($request->project->structure_id);

                if($structure && $structure->organization->user_id == auth()->id()) {
                    return $next($request);
                }
            }

            if($request->project->team_id) {
                $team = Auth::user()->allUserTeams()
                    ->where('id', $request->project->team_id)
                    ->get();

                $isFollower = ProjectUsers::where('project_id', $request->project->id)
                    ->where('user_id', Auth::id())
                    ->first();

                if ($team && $isFollower) {
                    return $next($request);
                }
            }
        }

        flash()->error('Access denied!');

        return redirect(route('projects.index'));
    }
}

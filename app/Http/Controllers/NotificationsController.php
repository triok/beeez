<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\TeamUsers;
use App\Transformers\NotificationTransformer;
use Illuminate\Http\Request;

class NotificationsController extends Controller
{
    protected $transformer;

    function __construct()
    {
        $this->middleware('auth');

        $this->transformer = new NotificationTransformer();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        auth()->user()->unreadNotifications->markAsRead();

        $notifications = auth()->user()->notifications;

        $notifications = $this->transformer->transformCollection(
            $notifications->toArray()
        );

        return view('notifications.index', compact('notifications'));
    }

    /**
     * Update a resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|void
     */
    public function approve(Request $request)
    {
        $notification = auth()->user()
            ->notifications()
            ->find($request->get('id'));

        if (!$notification) {
            flash()->error('Access denied!');

            return redirect(route('notifications.index'));
        }

        $team_id = isset($notification->data['team_id']) ? $notification->data['team_id'] : 0;

        $team = Team::find($team_id);

        if (!$team) {
            flash()->error('Team not found!');

            return redirect(route('notifications.index'));
        }

        TeamUsers::where('team_id', $team_id)
            ->where('user_id', auth()->id())
            ->update(['is_approved' => true]);

        $notification->delete();

        flash()->success('Вы приняты в команду.');

        return redirect(route('notifications.index'));
    }

    /**
     * Update a resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|void
     * @throws \Exception
     */
    public function reject(Request $request)
    {
        $notification = auth()->user()
            ->notifications()
            ->find($request->get('id'));

        if (!$notification) {
            flash()->error('Access denied!');

            return redirect(route('notifications.index'));
        }

        $team_id = isset($notification->data['team_id']) ? $notification->data['team_id'] : 0;

        $team = Team::find($team_id);

        if (!$team) {
            flash()->error('Team not found!');

            return redirect(route('notifications.index'));
        }

        TeamUsers::where('team_id', $team_id)
            ->where('user_id', auth()->id())
            ->delete();

        $notification->delete();

        flash()->success('Вы удалены из команды.');

        return redirect(route('notifications.index'));
    }

    /**
     * Update a resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|void
     * @throws \Exception
     */
    public function destroy(Request $request)
    {
        $notification = auth()->user()
            ->notifications()
            ->find($request->get('id'));

        if (!$notification) {
            flash()->error('Access denied!');

            return redirect(route('notifications.index'));
        }

        $team_id = isset($notification->data['team_id']) ? $notification->data['team_id'] : 0;

        if ($team_id && $team = Team::find($team_id)) {
            TeamUsers::where('team_id', $team_id)
                ->where('user_id', auth()->id())
                ->delete();
        }

        $notification->delete();

        flash()->success('Уведомление удалено.');

        return redirect(route('notifications.index'));
    }
}

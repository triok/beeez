<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\OrganizationUsers;
use App\Models\Structure;
use App\Models\StructureUsers;
use App\Models\Team;
use App\Models\TeamUsers;
use App\Notifications\OrganizationUserApproveNotification;
use App\Notifications\OrganizationUserRejectNotification;
use App\Notifications\StructureUserApproveNotification;
use App\Notifications\StructureUserRejectNotification;
use App\Transformers\NotificationTransformer;
use App\User;
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

            return redirect($request->get('redirect', $request->get('redirect', route('notifications.index'))));
        }

        $team_id = isset($notification->data['team_id']) ? $notification->data['team_id'] : 0;

        $team = Team::find($team_id);

        if (!$team) {
            flash()->error('Team not found!');

            return redirect($request->get('redirect', route('notifications.index')));
        }

        TeamUsers::where('team_id', $team_id)
            ->where('user_id', auth()->id())
            ->update(['is_approved' => true]);

        $notification->update(['is_archived' => true]);

        flash()->success('Вы приняты в команду.');

        return redirect($request->get('redirect', route('notifications.index')));
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

            return redirect($request->get('redirect', route('notifications.index')));
        }

        $team_id = isset($notification->data['team_id']) ? $notification->data['team_id'] : 0;

        $team = Team::find($team_id);

        if (!$team) {
            flash()->error('Team not found!');

            return redirect($request->get('redirect', route('notifications.index')));
        }

        TeamUsers::where('team_id', $team_id)
            ->where('user_id', auth()->id())
            ->delete();

        $notification->update(['is_archived' => true]);

        flash()->success('Вы удалены из команды.');

        return redirect($request->get('redirect', route('notifications.index')));
    }

    /**
     * Update a resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|void
     */
    public function approveOrganization(Request $request)
    {
        $notification = auth()->user()
            ->notifications()
            ->find($request->get('id'));

        if (!$notification) {
            flash()->error('Access denied!');

            return redirect($request->get('redirect', $request->get('redirect', route('notifications.index'))));
        }

        $organization_id = isset($notification->data['organization_id']) ? $notification->data['organization_id'] : 0;

        $organization = Organization::find($organization_id);

        if (!$organization) {
            flash()->error('Organization not found!');

            return redirect($request->get('redirect', route('notifications.index')));
        }

        OrganizationUsers::where('organization_id', $organization_id)
            ->where('user_id', auth()->id())
            ->update(['is_approved' => true]);

        $notification->update(['is_archived' => true]);

        // Add notification
        $author = isset($notification->data['author']) ? $notification->data['author'] : 0;

        if ($recipient = User::find($author)) {
            $organizationUser = OrganizationUsers::where('organization_id', $organization_id)
                ->where('user_id', auth()->id())
                ->first();

            $recipient->notify(new OrganizationUserApproveNotification($organizationUser));
        }

        flash()->success('Вы приняты в организацию.');

        return redirect($request->get('redirect', route('notifications.index')));
    }

    /**
     * Update a resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|void
     * @throws \Exception
     */
    public function rejectOrganization(Request $request)
    {
        $notification = auth()->user()
            ->notifications()
            ->find($request->get('id'));

        if (!$notification) {
            flash()->error('Access denied!');

            return redirect($request->get('redirect', route('notifications.index')));
        }

        $organization_id = isset($notification->data['organization_id']) ? $notification->data['organization_id'] : 0;

        $organization = Organization::find($organization_id);

        if (!$organization) {
            flash()->error('Organization not found!');

            return redirect($request->get('redirect', route('notifications.index')));
        }

        // Add notification
        $author = isset($notification->data['author']) ? $notification->data['author'] : 0;

        if ($recipient = User::find($author)) {
            $organizationUser = OrganizationUsers::where('organization_id', $organization_id)
                ->where('user_id', auth()->id())
                ->first();

            $recipient->notify(new OrganizationUserRejectNotification($organizationUser));
        }

        OrganizationUsers::where('organization_id', $organization_id)
            ->where('user_id', auth()->id())
            ->delete();

        $notification->update(['is_archived' => true]);

        flash()->success('Вы удалены из организации.');

        return redirect($request->get('redirect', route('notifications.index')));
    }

    /**
     * Update a resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|void
     */
    public function approveStructure(Request $request)
    {
        $notification = auth()->user()
            ->notifications()
            ->find($request->get('id'));

        if (!$notification) {
            flash()->error('Access denied!');

            return redirect($request->get('redirect', $request->get('redirect', route('notifications.index'))));
        }

        $structure_id = isset($notification->data['structure_id']) ? $notification->data['structure_id'] : 0;

        $structure = Structure::find($structure_id);

        if (!$structure) {
            flash()->error('Organization not found!');

            return redirect($request->get('redirect', route('notifications.index')));
        }

        StructureUsers::where('structure_id', $structure_id)
            ->where('user_id', auth()->id())
            ->update(['is_approved' => true]);

        $notification->update(['is_archived' => true]);

        // Add notification
        $author = isset($notification->data['author']) ? $notification->data['author'] : 0;

        if ($recipient = User::find($author)) {
            $structureUser = StructureUsers::where('structure_id', $structure_id)
                ->where('user_id', auth()->id())
                ->first();

            $recipient->notify(new StructureUserApproveNotification($structureUser));
        }

        flash()->success('Вы приняты в организацию.');

        return redirect($request->get('redirect', route('notifications.index')));
    }

    /**
     * Update a resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|void
     * @throws \Exception
     */
    public function rejectStructure(Request $request)
    {
        $notification = auth()->user()
            ->notifications()
            ->find($request->get('id'));

        if (!$notification) {
            flash()->error('Access denied!');

            return redirect($request->get('redirect', route('notifications.index')));
        }

        $structure_id = isset($notification->data['structure_id']) ? $notification->data['structure_id'] : 0;

        $structure = Structure::find($structure_id);

        // Add notification
        $author = isset($notification->data['author']) ? $notification->data['author'] : 0;

        if ($recipient = User::find($author)) {
            $structureUser = StructureUsers::where('structure_id', $structure_id)
                ->where('user_id', auth()->id())
                ->first();

            $recipient->notify(new StructureUserRejectNotification($structureUser));
        }

        if (!$structure) {
            flash()->error('Organization not found!');

            return redirect($request->get('redirect', route('notifications.index')));
        }

        StructureUsers::where('structure_id', $structure_id)
            ->where('user_id', auth()->id())
            ->delete();

        $notification->update(['is_archived' => true]);

        flash()->success('Вы удалены из организации.');

        return redirect($request->get('redirect', route('notifications.index')));
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

            return redirect($request->get('redirect', route('notifications.index')));
        }

        $team_id = isset($notification->data['team_id']) ? $notification->data['team_id'] : 0;

        if ($team_id && $team = Team::find($team_id)) {
            TeamUsers::where('team_id', $team_id)
                ->where('user_id', auth()->id())
                ->delete();
        }

        $notification->delete();

        flash()->success('Уведомление удалено.');

        return redirect($request->get('redirect', route('notifications.index')));
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\OrganizationUsers;

class OrganizationsAccessController extends Controller
{
    /**
     * OrganizationsAccessController constructor.
     */
    function __construct()
    {
        $this->middleware('organization.approve')->only(['moderation', 'approve', 'reject']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function moderation()
    {
        $organizations = Organization::moderation()->paginate(request('count', 20));

        return view('organizations.moderation', compact('organizations'));
    }

    /**
     * Update a resource in storage.
     *
     * @param Organization $organization
     * @return \Illuminate\Http\RedirectResponse
     */
    public function approve(Organization $organization)
    {
        $organization->update(['status' => 'approved']);

        $this->updateNotification($organization);

        flash()->success('The organization has been approved!');

        return redirect()->back();
    }

    /**
     * Update a resource in storage.
     *
     * @param Organization $organization
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reject(Organization $organization)
    {
        $organization->update(['status' => 'rejected']);

        $this->updateNotification($organization);

        flash()->success('The organization has been rejected!');

        return redirect()->back();
    }

    /**
     * Update a resource in storage.
     *
     * @param Organization $organization
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addAdmin(Organization $organization)
    {
        if (auth()->id() != $organization->user_id) {
            flash()->error('Access denied!');

            return redirect()->back();
        }

        $connection = OrganizationUsers::where('organization_id', $organization->id)
            ->where('user_id', request('user_id'))
            ->first();

        if (!$connection) {
            flash()->error('Access denied!');

            return redirect(route('organizations.show', $organization));
        }

        OrganizationUsers::where('organization_id', $organization->id)
            ->where('user_id', request('user_id'))
            ->update(['is_admin' => true]);

        flash()->success('Доступ открыт.');

        return redirect(route('organizations.show', $organization));
    }

    /**
     * Update a resource in storage.
     *
     * @param Organization $organization
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteAdmin(Organization $organization)
    {
        if (auth()->id() != $organization->user_id) {
            flash()->error('Access denied!');

            return redirect()->back();
        }

        $connection = OrganizationUsers::where('organization_id', $organization->id)
            ->where('user_id', request('user_id'))
            ->first();

        if (!$connection) {
            flash()->error('Access denied!');

            return redirect(route('organizations.show', $organization));
        }

        OrganizationUsers::where('organization_id', $organization->id)
            ->where('user_id', request('user_id'))
            ->update(['is_admin' => false]);

        flash()->success('Доступ закрыт.');

        return redirect(route('organizations.show', $organization));
    }

    /**
     * @param $organization
     */
    private function updateNotification($organization)
    {
        $notifications = auth()->user()
            ->notifications()
            ->where('type', 'App\Notifications\OrganizationNotification')
            ->get();


        foreach ($notifications as $notification) {
            if ($notification['data']['id'] == $organization->id) {
                $notification = auth()->user()
                    ->notifications()
                    ->find($notification['id']);

                if ($notification) {
                    $notification->update(['is_archived' => true]);
                }
            }
        }
    }
}

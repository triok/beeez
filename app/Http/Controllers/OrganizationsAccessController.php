<?php

namespace App\Http\Controllers;

use App\Models\Organization;

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
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function addAdmin(Organization $organization)
    {
        $this->authorize('addAdmin', $organization);

        if (!$connection = $this->getOrganizationUser($organization)) {
            return redirect()->back();
        }

        $connection->pivot->update(['is_admin' => true]);

        flash()->success('Доступ открыт.');

        return redirect()->back();
    }

    /**
     * Update a resource in storage.
     *
     * @param Organization $organization
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function deleteAdmin(Organization $organization)
    {
        $this->authorize('addAdmin', $organization);

        if (!$connection = $this->getOrganizationUser($organization)) {
            return redirect()->back();
        }

        $connection->pivot->update(['is_admin' => false]);

        flash()->success('Доступ закрыт.');

        return redirect()->back();
    }

    /**
     * Update a resource in storage.
     *
     * @param Organization $organization
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function addFullAccess(Organization $organization)
    {
        $this->authorize('addFullAccess', $organization);

        if (!$connection = $this->getOrganizationUser($organization)) {
            return redirect()->back();
        }

        $connection->pivot->update(['is_owner' => true]);

        flash()->success('Доступ открыт.');

        return redirect()->back();
    }

    /**
     * Update a resource in storage.
     *
     * @param Organization $organization
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function deleteFullAccess(Organization $organization)
    {
        $this->authorize('addFullAccess', $organization);

        if (!$connection = $this->getOrganizationUser($organization)) {
            return redirect()->back();
        }

        $connection->pivot->update(['is_owner' => false]);

        flash()->success('Доступ закрыт.');

        return redirect()->back();
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

    /**
     * Check the user exist in the organization.
     *
     * @param Organization $organization
     * @return Organization|bool
     */
    private function getOrganizationUser(Organization $organization)
    {
        $connection = $organization->users()
            ->where('user_id', request('user_id'))
            ->first();

        if ($connection) {
            return $connection;
        }

        flash()->error('Пользователь еще не добавлен в организацию!');

        return false;
    }
}

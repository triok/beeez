<?php

namespace App\Policies;

use App\Models\Organization;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrganizationPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can update the organization.
     *
     * @param User $user
     * @param Organization $organization
     * @return mixed
     */
    public function update(User $user, Organization $organization)
    {
        return $user->isOrganizationAdmin($organization);
    }

    /**
     * Determine whether the user can add an admin access to the organization.
     *
     * @param User $user
     * @param Organization $organization
     * @return mixed
     */
    public function addAdmin(User $user, Organization $organization)
    {
        return $user->isOrganizationFullAccess($organization);
    }

    /**
     * Determine whether the user can add a fu access to the organization.
     *
     * @param User $user
     * @param Organization $organization
     * @return mixed
     */
    public function addFullAccess(User $user, Organization $organization)
    {
        return $user->isOrganizationOwner($organization);
    }
}
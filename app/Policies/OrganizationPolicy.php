<?php

namespace App\Policies;

use App\Models\Organization;
use App\Models\Structure;
use App\Models\StructureUsers;
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
     * Determine whether the user can update the organization.
     *
     * @param User $user
     * @param Organization $organization
     * @param Structure|null $structure
     * @return mixed
     */
    public function updateStructure(User $user, Organization $organization, Structure $structure = null)
    {
        if($user->isOrganizationFullAccess($organization)) {
            return true;
        }

        if($structure) {
            $connection = StructureUsers::where('user_id', $user->id)
                ->where('structure_id', $structure->id)
                ->first();

            if($connection && $connection->can_add_user) {
                return true;
            }
        }

        return false;
    }

    /**
     * Determine whether the user can update the organization.
     *
     * @param User $user
     * @param Organization $organization
     * @return mixed
     */
    public function updateVacancies(User $user, Organization $organization)
    {
        return $user->isOrganizationFullAccess($organization);
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

    /**
     * Determine whether the user can update the organization.
     *
     * @param User $user
     * @param Structure|null $structure
     * @return mixed
     */
    public function addProjectToStructure(User $user, Structure $structure)
    {
        if($user->isOrganizationFullAccess($structure->organization)) {
            return true;
        }

        if($structure) {
            $connection = StructureUsers::where('user_id', $user->id)
                ->where('structure_id', $structure->id)
                ->first();

            if($connection && $connection->can_add_project) {
                return true;
            }
        }

        return true;
    }
}
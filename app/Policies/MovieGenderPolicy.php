<?php

namespace App\Policies;

use App\Models\MovieGender;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MovieGenderPolicy
{
    use HandlesAuthorization;

    public function before(User $user, $ability)
    {
        if ($user->isGranted(User::ROLE_SUPERADMIN)) {
            return true;
        }
    }

    /**
     * Determine whether the user can view any models.
     *
     * @param \App\Models\User $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->isGranted(User::ROLE_USER);
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\MovieGender $movieGender
     * @return mixed
     */
    public function view(User $user, MovieGender $movieGender)
    {
        return $user->isGranted(User::ROLE_USER);
    }

    /**
     * Determine whether the user can create models.
     *
     * @param \App\Models\User $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->isGranted(User::ROLE_USER);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\MovieGender $movieGender
     * @return mixed
     */
    public function update(User $user, MovieGender $movieGender)
    {
        return $user->isGranted(User::ROLE_USER) && $user->id === $movieGender->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\MovieGender $movieGender
     * @return mixed
     */
    public function delete(User $user, MovieGender $movieGender)
    {
        return $user->isGranted(User::ROLE_USER);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\MovieGender $movieGender
     * @return mixed
     */
    public function restore(User $user, MovieGender $movieGender)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\MovieGender $movieGender
     * @return mixed
     */
    public function forceDelete(User $user, MovieGender $movieGender)
    {

    }
}

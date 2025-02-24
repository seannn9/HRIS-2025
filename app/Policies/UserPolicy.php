<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        if ($user->isEmployee()) return false;
        
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, User $model): bool
    {
        if ($user->isAdmin()) return true;
        else if ($user->id == $model->id) return true;
        else if ($user->isHr() && ($model->isAdmin() || $model->isHr())) return false;
        else if ($user->isHr()) return true;
        else return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        if ($user->isEmployee()) return false;

        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, User $model): bool
    {
        if ($user->isAdmin()) return true;
        else if ($user->id == $model->id) return true;
        else if ($user->isHr() && ($model->isAdmin() || $model->isHr())) return false;
        else if ($user->isHr()) return true;
        else return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $model): bool
    {
        if ($user->id == $model->id) return false;
        else if ($user->isAdmin()) return true;
        else if ($user->isHr() && $model->isEmployee()) return true;
        else return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, User $model): bool
    {
        if ($user->isAdmin()) return true;

        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, User $model): bool
    {
        if (($user->id == $model->id) && $user->isAdmin())
            return false;
        
        return true;
    }
}

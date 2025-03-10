<?php

namespace App\Policies;

use App\Models\Document;
use App\Models\User;

class DocumentPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isHr();
    }

    public function view(User $user, Document $document): bool
    {
        return $user->isAdmin() || 
               $user->isHr() || 
               $document->employee_id === $user->employee->id;
    }

    public function create(User $user): bool
    {
        return true; // Assuming all users can submit documents
    }

    public function update(User $user, Document $document): bool
    {
        return $user->isAdmin()
            || $user->isHr() 
            || $document->employee_id === $user->employee->id && $document->isPending();
    }

    public function delete(User $user, Document $document): bool
    {
        return $user->isAdmin()
            || $user->isHr() 
            || $document->employee_id === $user->employee->id && $document->isPending();
    }
}
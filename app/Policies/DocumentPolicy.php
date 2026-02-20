<?php

namespace App\Policies;

use App\Models\Document;
use App\Models\User;

class DocumentPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Document $document): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($document->access_level === 'public') {
            return true;
        }

        if ($document->access_level === 'department') {
            return $user->isManager() && $user->department_id === $document->department_id;
        }

        if ($document->access_level === 'private') {
            return $user->id === $document->uploaded_by;
        }

        return false;
    }

    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isManager();
    }

    public function update(User $user, Document $document): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isManager()) {
            return $user->id === $document->uploaded_by;
        }

        return false;
    }

    public function delete(User $user, Document $document): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isManager()) {
            return $user->id === $document->uploaded_by;
        }

        return false;
    }
}

<?php

namespace App\Livewire\Admin\Pages\Users;

use App\Models\DeletedUserLog;
use App\Models\Employee;
use App\Models\User;
use App\Notifications\UserArchivedNotification;
use App\Notifications\UserRestoredNotification;
use App\Notifications\UserSuspendedNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Livewire\Component;
use Livewire\WithPagination;

class ManageUsers extends Component
{
    use WithPagination;

    public $roleFilter = 'all';
    public $statusFilter = 'all';
    public $search = '';
    public $activeTab = 'active';

    public $showUserModal = false;
    public $selectedUser = null;

    public $showSuspendModal = false;
    public $showArchiveModal = false;
    public $actionUserId = null;
    public $actionReason = '';
    public $actionUserName = '';

    protected $queryString = ['roleFilter', 'statusFilter', 'search', 'activeTab'];

    public function render()
    {
        $users = collect();
        $permanentlyDeletedUsers = collect();

        if ($this->activeTab === 'soft_deleted') {
            $query = User::with('roles', 'shop', 'employee')->onlyTrashed();

            if ($this->roleFilter !== 'all') {
                $query->whereHas('roles', function ($q) {
                    $q->where('name', $this->roleFilter);
                });
            }

            if (!empty($this->search)) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%');
                });
            }

            $users = $query->orderBy('deleted_at', 'desc')->paginate(15);
        } elseif ($this->activeTab === 'permanently_deleted') {
            $query = DeletedUserLog::query();

            if (!empty($this->search)) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%');
                });
            }

            $permanentlyDeletedUsers = $query->orderBy('deleted_at', 'desc')->paginate(15);
        } else {
            $query = User::with('roles', 'shop', 'employee')->whereNull('deleted_at');

            if ($this->statusFilter === 'active') {
                $query->where('is_active', true);
            } elseif ($this->statusFilter === 'suspended') {
                $query->where('is_active', false);
            } elseif ($this->statusFilter === 'deactivated_by_owner') {
                $query->whereHas('employee', function ($q) {
                    $q->where('deactivated_by', 'owner');
                });
            } elseif ($this->statusFilter === 'suspended_by_admin') {
                $query->whereHas('employee', function ($q) {
                    $q->where('deactivated_by', 'super_admin');
                });
            } elseif ($this->statusFilter === 'deleted_by_owner') {
                // ✅ Added filter for deleted by owner
                $query->whereHas('employee', function ($q) {
                    $q->onlyTrashed();
                });
            }

            if ($this->roleFilter !== 'all') {
                $query->whereHas('roles', function ($q) {
                    $q->where('name', $this->roleFilter);
                });
            }

            if (!empty($this->search)) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%');
                });
            }

            $users = $query->orderBy('created_at', 'desc')->paginate(15);
        }

        return view('livewire.admin.pages.users.manage-users', [
            'users' => $users,
            'permanentlyDeletedUsers' => $permanentlyDeletedUsers,
        ])->layout('components.layouts.admin');
    }

    public function setTab($tab)
    {
        $this->activeTab = $tab;
        $this->resetPage();
    }

    public function viewUserDetails($userId)
    {
        $this->selectedUser = User::with(['shop', 'employee' => function ($q) {
            $q->withTrashed()->with('branch');
        }])
            ->withTrashed()
            ->findOrFail($userId);

        $this->showUserModal = true;
    }

    public function closeUserModal()
    {
        $this->showUserModal = false;
        $this->selectedUser = null;
    }

    public function openSuspendModal($userId)
    {
        $user = User::findOrFail($userId);

        if ($user->id === Auth::id()) {
            session()->flash('error', 'You cannot change your own status.');
            return;
        }

        $this->actionUserId = $userId;
        $this->actionUserName = $user->name;
        $this->actionReason = '';
        $this->showSuspendModal = true;
        $this->resetErrorBag();
    }

    public function closeSuspendModal()
    {
        $this->showSuspendModal = false;
        $this->actionUserId = null;
        $this->actionUserName = '';
        $this->actionReason = '';
        $this->resetErrorBag();
    }

    public function openArchiveModal($userId)
    {
        $user = User::findOrFail($userId);

        if ($user->id === Auth::id()) {
            session()->flash('error', 'You cannot archive your own account.');
            return;
        }

        $this->actionUserId = $userId;
        $this->actionUserName = $user->name;
        $this->actionReason = '';
        $this->showArchiveModal = true;
        $this->resetErrorBag();
    }

    public function closeArchiveModal()
    {
        $this->showArchiveModal = false;
        $this->actionUserId = null;
        $this->actionUserName = '';
        $this->actionReason = '';
        $this->resetErrorBag();
    }

    public function confirmSuspend()
    {
        $this->validate([
            'actionReason' => 'required|string|min:10|max:500',
        ], [
            'actionReason.required' => 'Please provide a reason for suspending this user.',
            'actionReason.min' => 'Please provide at least 10 characters.',
            'actionReason.max' => 'Reason is too long (max 500 characters).',
        ]);

        $user = User::findOrFail($this->actionUserId);

        if ($user->id === Auth::id()) {
            session()->flash('error', 'You cannot change your own status.');
            $this->closeSuspendModal();
            return;
        }

        $user->is_active = false;
        $user->save();

        if ($user->employee) {
            $user->employee->is_active = false;
            $user->employee->deactivated_by = 'super_admin';
            $user->employee->save();
        }

        try {
            Notification::send($user, new UserSuspendedNotification($user, $this->actionReason, Auth::user()->name));
        } catch (\Exception $e) {
            Log::warning('Failed to send suspension email', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);
        }

        $userName = $user->name;
        $this->closeSuspendModal();

        session()->flash('message', "User \"{$userName}\" suspended successfully. They have been notified with the reason.");
    }

    public function confirmArchive()
    {
        $this->validate([
            'actionReason' => 'required|string|min:10|max:500',
        ], [
            'actionReason.required' => 'Please provide a reason for archiving this user.',
            'actionReason.min' => 'Please provide at least 10 characters.',
            'actionReason.max' => 'Reason is too long (max 500 characters).',
        ]);

        $user = User::findOrFail($this->actionUserId);

        if ($user->id === Auth::id()) {
            session()->flash('error', 'You cannot archive your own account.');
            $this->closeArchiveModal();
            return;
        }

        try {
            Notification::send($user, new UserArchivedNotification($user, $this->actionReason, Auth::user()->name));
        } catch (\Exception $e) {
            Log::warning('Failed to send archive email', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);
        }

        $employee = Employee::where('user_id', $user->id)->first();
        if ($employee) {
            $employee->delete();
        }

        $user->delete();

        $userName = $user->name;
        $this->closeArchiveModal();

        session()->flash('message', "User \"{$userName}\" archived successfully. They have been notified with the reason.");
    }

    public function restoreUser($userId)
{
    $user = User::withTrashed()->findOrFail($userId);
    $user->restore();

    $user->update(['is_active' => true]);

    // ✅ Use withTrashed() to find the employee record regardless of its state
    $employee = Employee::withTrashed()->where('user_id', $userId)->first();
    if ($employee) {
        $employee->restore(); // Un-delete if it was soft-deleted
        $employee->update([
            'is_active' => true,
            'deactivated_by' => null, // Remove any suspension flags
        ]);
    }

    try {
        Notification::send($user, new UserRestoredNotification($user));
    } catch (\Exception $e) {
        Log::warning('Failed to send restore email', [
            'user_id' => $user->id,
            'error' => $e->getMessage(),
        ]);
    }

    session()->flash('message', 'User record restored successfully. They have been notified.');
}

    public function forceDeleteUser($userId)
    {
        $user = User::withTrashed()->findOrFail($userId);

        if ($user->id === Auth::id()) {
            session()->flash('error', 'You cannot permanently delete your own account.');
            return;
        }

        DeletedUserLog::create([
            'original_user_id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'shop_name' => $user->shop?->shop_name,
            'roles' => $user->getRoleNames()->implode(', '),
            'deleted_by' => Auth::user()->name,
            'deleted_at' => now(),
        ]);

        $employee = Employee::withTrashed()->where('user_id', $userId)->first();
        if ($employee) {
            $employee->forceDelete();
        }

        $user->forceDelete();
        session()->flash('message', 'User permanently deleted. Record saved to log.');
    }

    public function activateUser($userId)
    {
        $user = User::findOrFail($userId);

        if ($user->id === Auth::id()) {
            session()->flash('error', 'You cannot change your own status.');
            return;
        }

        $user->is_active = true;
        $user->save();

        if ($user->employee) {
            $user->employee->is_active = true;
            $user->employee->deactivated_by = null;
            $user->employee->save();
        }

        try {
            Notification::send($user, new UserRestoredNotification($user));
        } catch (\Exception $e) {
            Log::warning('Failed to send activation email', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);
        }

        session()->flash('message', 'User activated successfully. They have been notified.');
    }

    public function resetFilters()
    {
        $this->roleFilter = 'all';
        $this->statusFilter = 'all';
        $this->search = '';
        $this->activeTab = 'active';
    }
}

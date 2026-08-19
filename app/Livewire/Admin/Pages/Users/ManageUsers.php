<?php

namespace App\Livewire\Admin\Pages\Users;

use App\Models\DeletedUserLog;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class ManageUsers extends Component
{
    use WithPagination;

    public $roleFilter = 'all';
    public $statusFilter = 'all';
    public $search = '';
    public $activeTab = 'active'; // 'active', 'soft_deleted', 'permanently_deleted'

    protected $queryString = ['roleFilter', 'statusFilter', 'search', 'activeTab'];

    public function render()
    {
        $users = collect();
        $permanentlyDeletedUsers = collect();

        if ($this->activeTab === 'soft_deleted') {
            // ✅ Show soft-deleted users
            $query = User::with('roles', 'shop')->onlyTrashed();

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
            // ✅ Show permanently deleted users from log
            $query = DeletedUserLog::query();

            if (!empty($this->search)) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%');
                });
            }

            $permanentlyDeletedUsers = $query->orderBy('deleted_at', 'desc')->paginate(15);
        } else {
            // ✅ Active users (default)
            $query = User::with('roles', 'shop')->whereNull('deleted_at');

            if ($this->statusFilter === 'active') {
                $query->where('is_active', true);
            } elseif ($this->statusFilter === 'suspended') {
                $query->where('is_active', false);
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

    public function deleteUser($userId)
    {
        $user = User::findOrFail($userId);

        if ($user->id === Auth::id()) {
            session()->flash('error', 'You cannot delete your own account.');
            return;
        }

        $user->delete();
        session()->flash('message', 'User soft deleted successfully.');
    }

    public function restoreUser($userId)
    {
        $user = User::withTrashed()->findOrFail($userId);
        $user->restore();
        session()->flash('message', 'User restored successfully.');
    }

    public function forceDeleteUser($userId)
    {
        $user = User::withTrashed()->findOrFail($userId);

        if ($user->id === Auth::id()) {
            session()->flash('error', 'You cannot permanently delete your own account.');
            return;
        }

        // ✅ Save to log before force deleting
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

        $user->forceDelete();
        session()->flash('message', 'User permanently deleted. Record saved to log.');
    }

    public function toggleUserStatus($userId)
    {
        $user = User::findOrFail($userId);

        if ($user->id === Auth::id()) {
            session()->flash('error', 'You cannot change your own status.');
            return;
        }

        $user->is_active = !$user->is_active;
        $user->save();

        session()->flash('message', 'User status updated successfully.');
    }

    public function resetFilters()
    {
        $this->roleFilter = 'all';
        $this->statusFilter = 'all';
        $this->search = '';
        $this->activeTab = 'active';
    }
}

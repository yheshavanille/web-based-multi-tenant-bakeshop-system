<?php

namespace App\Livewire\Admin\Pages\Users;

use App\Models\DeletedUserLog;
use App\Models\Employee;
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
    public $activeTab = 'active';

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

        // Soft delete employee too if user is an employee
        $employee = Employee::where('user_id', $userId)->first();
        if ($employee) {
            $employee->delete();
        }

        $user->delete();
        session()->flash('message', 'User and associated employee record soft deleted successfully.');
    }

    public function restoreUser($userId)
    {
        $user = User::withTrashed()->findOrFail($userId);
        $user->restore();

        // Restore employee if exists
        $employee = Employee::onlyTrashed()->where('user_id', $userId)->first();
        if ($employee) {
            $employee->restore();
        }

        session()->flash('message', 'User and associated employee record restored successfully.');
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

        // Force delete employee if exists
        $employee = Employee::withTrashed()->where('user_id', $userId)->first();
        if ($employee) {
            $employee->forceDelete();
        }

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

    /**
     * Get employee status label for display
     */
    public function getEmployeeStatus($user)
    {
        $employee = $user->employee;

        if (!$employee) {
            return null;
        }

        if ($employee->trashed()) {
            return '🗑️ Deleted by Employer';
        }

        if (!$employee->is_active) {
            return '🟡 Disabled by Employer';
        }

        return null;
    }
}

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

    // ✅ User Details Modal
    public $showUserModal = false;
    public $selectedUser = null;

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
                // ✅ Users whose employee is deactivated by owner
                $query->whereHas('employee', function ($q) {
                    $q->where('is_active', false)
                        ->where('deactivated_by', 'owner');
                });
            } elseif ($this->statusFilter === 'suspended_by_admin') {
                // ✅ Users suspended by Super Admin
                $query->whereHas('employee', function ($q) {
                    $q->where('is_active', false)
                        ->where('deactivated_by', 'super_admin');
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

    // ✅ View User Details
    public function viewUserDetails($userId)
    {
        $this->selectedUser = User::with(['shop', 'employee.branch'])
            ->withTrashed()
            ->findOrFail($userId);
        $this->showUserModal = true;
    }

    // ✅ Close User Modal
    public function closeUserModal()
    {
        $this->showUserModal = false;
        $this->selectedUser = null;
    }

    public function deleteUser($userId)
    {
        $user = User::findOrFail($userId);

        if ($user->id === Auth::id()) {
            session()->flash('error', 'You cannot delete your own account.');
            return;
        }

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

        $employee = Employee::onlyTrashed()->where('user_id', $userId)->first();
        if ($employee) {
            $employee->restore();
        }

        session()->flash('message', 'User record restored successfully.');
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

    // ✅ FIXED: Sync user and employee status
    public function toggleUserStatus($userId)
    {
        $user = User::findOrFail($userId);

        if ($user->id === Auth::id()) {
            session()->flash('error', 'You cannot change your own status.');
            return;
        }

        // ✅ Toggle user status
        $newStatus = !$user->is_active;
        $user->is_active = $newStatus;
        $user->save();

        // ✅ If user has an employee record, sync the status
        if ($user->employee) {
            $employee = $user->employee;

            // If activating, also activate the employee and clear deactivated_by
            if ($newStatus) {
                $employee->is_active = true;
                $employee->deactivated_by = null;
            } else {
                // If suspending, deactivate employee and mark as deactivated_by = 'super_admin'
                $employee->is_active = false;
                $employee->deactivated_by = 'super_admin';
            }
            $employee->save();
        }

        $message = $newStatus ? 'User activated successfully.' : 'User suspended successfully.';
        session()->flash('message', $message);
    }

    // ✅ Get employee status with deactivated_by check
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
            // ✅ Check if deactivated by Super Admin or Owner
            if ($employee->deactivated_by === 'super_admin') {
                return '🔴 Suspended by Super Admin';
            }
            return '🟡 Deactivated by Owner';
        }

        return null;
    }

    public function resetFilters()
    {
        $this->roleFilter = 'all';
        $this->statusFilter = 'all';
        $this->search = '';
        $this->activeTab = 'active';
    }
}

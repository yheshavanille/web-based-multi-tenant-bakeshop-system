<?php

namespace App\Livewire\Owner\Employees;

use App\Models\Branch;
use App\Models\Employee;
use App\Models\EmployeeActivity;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class ManageEmployees extends Component
{
    use WithFileUploads;

    public $employees = [];
    public $branches = [];
    public $showForm = false;
    public $editing = false;
    public $employeeId;
    public $name = '';
    public $email = '';
    public $phone = '';
    public $role = '';
    public $branch_id = '';
    public $selectedBranchId = null;
    public $password = '';
    public $password_confirmation = '';
    public $showDeleted = false;
    public $search = '';

    public $recentEmployeeActivities = [];

    public $new_profile_picture;
    public $temp_profile_picture_preview = null;
    public $existing_profile_picture = null;
    public $removeImage = false;

    public $showResetPassword = false;
    public $new_password = '';
    public $new_password_confirmation = '';

    // ✅ NEW: Employee Details Modal
    public $showDetailsModal = false;
    public $selectedEmployee = null;
    public $moderationInfo = null; // array|null — reason, by, when, type

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'phone' => 'required|string|regex:/^09[0-9]{9}$/|max:11',
        'role' => 'required|in:order_manager,inventory_manager',
        'branch_id' => 'required|exists:branches,id',
        'password' => 'required|min:8|confirmed',
    ];

    public function mount($branch = null)
    {
        $shop = Auth::user()->shop;
        $this->branches = Branch::where('shop_id', $shop->id)
            ->where('is_active', true)
            ->get();

        $this->selectedBranchId = $branch;
        $this->loadEmployees();
        $this->loadRecentEmployeeActivities();
    }

    public function loadRecentEmployeeActivities()
    {
        $shop = Auth::user()->shop;

        $this->recentEmployeeActivities = EmployeeActivity::where('shop_id', $shop->id)
            ->with(['employee.user', 'employee.branch'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
    }

    public function loadEmployees()
    {
        $shop = Auth::user()->shop;

        if ($this->showDeleted) {
            $query = Employee::onlyTrashed()
                ->with(['user', 'branch'])
                ->where('shop_id', $shop->id);
        } else {
            $query = Employee::with(['user', 'branch'])
                ->where('shop_id', $shop->id)
                ->whereNull('employees.deleted_at');
        }

        if ($this->selectedBranchId) {
            $query->where('branch_id', $this->selectedBranchId);
        }

        if (!empty($this->search)) {
            $searchTerm = '%' . $this->search . '%';
            $query->whereHas('user', function ($q) use ($searchTerm) {
                $q->where('name', 'like', $searchTerm)
                    ->orWhere('email', 'like', $searchTerm)
                    ->orWhere('phone', 'like', $searchTerm);
            });
        }

        $this->employees = $query->get();
    }

    public function updatedSearch()
    {
        $this->loadEmployees();
    }

    public function updatedSelectedBranchId()
    {
        $this->loadEmployees();
    }

    public function updatedNewProfilePicture()
    {
        $this->validate([
            'new_profile_picture' => 'image|max:2048',
        ]);

        $this->temp_profile_picture_preview = $this->new_profile_picture->temporaryUrl();
        $this->removeImage = false;
    }

    public function clearSearch()
    {
        $this->search = '';
        $this->loadEmployees();
    }

    public function toggleDeleted()
    {
        $this->showDeleted = !$this->showDeleted;
        $this->loadEmployees();
    }

    public function createNew()
    {
        $this->reset(['name', 'email', 'phone', 'role', 'branch_id', 'password', 'password_confirmation', 'employeeId']);
        $this->reset(['showResetPassword', 'new_password', 'new_password_confirmation']);
        $this->reset(['new_profile_picture', 'temp_profile_picture_preview', 'existing_profile_picture', 'removeImage']);
        $this->editing = false;
        $this->showForm = true;
    }

    public function edit($employeeId)
    {
        $employee = Employee::with('user')->findOrFail($employeeId);

        if (!$employee->user || $employee->user->trashed()) {
            session()->flash('error', 'This employee cannot be edited because their account has been deleted.');
            return;
        }

        $shop = Auth::user()->shop;

        $isSuspendedByAdmin = !$employee->user->is_active && $employee->is_active;

        if ($isSuspendedByAdmin) {
            session()->flash('error', 'This employee cannot be edited because their account has been suspended by Super Admin.');
            return;
        }

        $this->employeeId = $employee->id;
        $this->name = $employee->user->name;
        $this->email = $employee->user->email;
        $this->phone = $employee->user->phone ?? '';
        $this->role = $employee->role;
        $this->branch_id = $employee->branch_id;
        $this->editing = true;
        $this->showForm = true;
        $this->password = '';
        $this->password_confirmation = '';

        $this->existing_profile_picture = $employee->user->profile_picture;

        $this->reset(['showResetPassword', 'new_password', 'new_password_confirmation']);
        $this->reset(['new_profile_picture', 'temp_profile_picture_preview', 'removeImage']);
    }

    public function cancel()
    {
        $this->showForm = false;
        $this->reset(['name', 'email', 'phone', 'role', 'branch_id', 'password', 'password_confirmation', 'employeeId']);
        $this->reset(['showResetPassword', 'new_password', 'new_password_confirmation']);
        $this->reset(['new_profile_picture', 'temp_profile_picture_preview', 'existing_profile_picture', 'removeImage']);
    }

    public function removeProfilePicture()
    {
        $this->removeImage = true;
        $this->temp_profile_picture_preview = null;
        $this->new_profile_picture = null;
    }

    public function toggleResetPassword()
    {
        $this->showResetPassword = !$this->showResetPassword;
        if (!$this->showResetPassword) {
            $this->reset(['new_password', 'new_password_confirmation']);
        }
    }

    public function save()
    {
        $shop = Auth::user()->shop;

        if ($this->new_profile_picture) {
            $this->validate([
                'new_profile_picture' => 'image|max:2048',
            ]);
        }

        if ($this->editing) {
            $employee = Employee::findOrFail($this->employeeId);

            if (!$employee->user || $employee->user->trashed()) {
                session()->flash('error', 'This employee cannot be edited because their account has been deleted.');
                return;
            }

            $isSuspendedByAdmin = !$employee->user->is_active && $employee->is_active;

            if ($isSuspendedByAdmin) {
                session()->flash('error', 'This employee cannot be edited because their account has been suspended by Super Admin.');
                return;
            }

            $rules = [
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . $employee->user_id,
                'phone' => 'required|string|regex:/^09[0-9]{9}$/|max:11',
                'role' => 'required|in:order_manager,inventory_manager',
                'branch_id' => 'required|exists:branches,id',
            ];

            if (!empty($this->new_password) || !empty($this->new_password_confirmation)) {
                $rules['new_password'] = 'required|min:8|confirmed';
            }

            $this->validate($rules);

            $user = User::findOrFail($employee->user_id);

            $updateData = [
                'name' => $this->name,
                'email' => $this->email,
                'phone' => $this->phone,
            ];

            if ($this->new_profile_picture) {
                if ($user->profile_picture) {
                    Storage::disk('public')->delete($user->profile_picture);
                }
                $path = $this->new_profile_picture->store('profile-pictures', 'public');
                $updateData['profile_picture'] = $path;
            } elseif ($this->removeImage) {
                if ($user->profile_picture) {
                    Storage::disk('public')->delete($user->profile_picture);
                }
                $updateData['profile_picture'] = null;
            }

            $user->update($updateData);

            $employee->update([
                'role' => $this->role,
                'branch_id' => $this->branch_id,
            ]);

            if (!empty($this->new_password)) {
                $user->update([
                    'password' => Hash::make($this->new_password),
                ]);

                EmployeeActivity::create([
                    'employee_id' => $employee->id,
                    'shop_id'     => $employee->shop_id,
                    'action'      => 'password_changed_by_owner',
                    'description' => Auth::user()->name . ' changed ' . $user->name . "'s password",
                ]);
            }

            session()->flash('message', 'Employee updated successfully!');
        } else {
            $this->validate();

            $userData = [
                'name' => $this->name,
                'email' => $this->email,
                'phone' => $this->phone,
                'password' => Hash::make($this->password),
                'role' => 'employee',
                'shop_id' => $shop->id,
            ];

            if ($this->new_profile_picture) {
                $path = $this->new_profile_picture->store('profile-pictures', 'public');
                $userData['profile_picture'] = $path;
            }

            $user = User::create($userData);
            $user->assignRole('employee');

            $newEmployee = Employee::create([
                'user_id' => $user->id,
                'shop_id' => $shop->id,
                'branch_id' => $this->branch_id,
                'role' => $this->role,
                'is_active' => true,
            ]);

            EmployeeActivity::create([
                'employee_id' => $newEmployee->id,
                'shop_id'     => $shop->id,
                'action'      => 'employee_created',
                'description' => Auth::user()->name . ' created account for ' . $user->name,
            ]);

            session()->flash('message', 'Employee created successfully!');
        }

        $this->cancel();
        $this->loadEmployees();
        $this->loadRecentEmployeeActivities();
    }

    public function toggleStatus($employeeId)
    {
        $employee = Employee::findOrFail($employeeId);

        if (!$employee->user || $employee->user->trashed()) {
            session()->flash('error', 'Cannot change status - user account is deleted.');
            return;
        }

        $isSuspendedByAdmin = !$employee->user->is_active && $employee->deactivated_by === 'super_admin';

        if ($isSuspendedByAdmin) {
            session()->flash('error', 'Cannot change status - user account is suspended by Super Admin.');
            return;
        }

        $newStatus = !$employee->is_active;
        $employee->update([
            'is_active' => $newStatus,
            'deactivated_by' => $newStatus ? null : 'owner',
        ]);

        $employee->user->update(['is_active' => $newStatus]);

        $this->loadEmployees();
        session()->flash('message', 'Employee status updated to ' . ($newStatus ? 'Active' : 'Inactive') . '.');
    }

    public function delete($employeeId)
    {
        $employee = Employee::findOrFail($employeeId);
        $employeeName = $employee->user?->name ?? 'Unknown Employee';

        $employee->delete();

        $this->loadEmployees();
        session()->flash('message', 'Employee "' . $employeeName . '" deleted successfully.');
    }

    public function restore($employeeId)
    {
        $employee = Employee::onlyTrashed()->findOrFail($employeeId);

        if (!$employee->user || $employee->user->trashed()) {
            session()->flash('error', 'Cannot restore - the user account has been deleted by Super Admin.');
            return;
        }

        if (!$employee->user->is_active) {
            session()->flash('error', 'Cannot restore - the user account is suspended. Please contact Super Admin.');
            return;
        }

        $employee->restore();

        $this->loadEmployees();
        session()->flash('message', 'Employee "' . $employee->user->name . '" restored successfully.');
    }

    // ✅ NEW: Open the Employee Details modal
    public function viewDetails($employeeId)
    {
        $employee = Employee::with(['user', 'branch'])
            ->withTrashed()
            ->findOrFail($employeeId);

        // Security: only show employees of this shop
        if ($employee->shop_id !== Auth::user()->shop->id) {
            abort(403);
        }

        $this->selectedEmployee = $employee;
        $this->moderationInfo = null;

        // ✅ Try to pull the most recent moderation notification for this user
        $user = $employee->user;

        if ($user) {
            $modNotification = $user->notifications()
                ->whereIn('data->type', ['user_suspended', 'user_archived', 'user_restored'])
                ->latest()
                ->first();

            if ($modNotification) {
                $data = $modNotification->data;

                $this->moderationInfo = [
                    'type' => $data['type'] ?? 'unknown',
                    'reason' => $data['reason'] ?? null,
                    'by' => $data['by'] ?? 'Super Admin',
                    'when' => $modNotification->created_at,
                ];
            }
        }

        $this->showDetailsModal = true;
    }

    public function closeDetailsModal()
    {
        $this->showDetailsModal = false;
        $this->selectedEmployee = null;
        $this->moderationInfo = null;
    }

    public function render()
    {
        return view('livewire.owner.employees.manage-employees')
            ->layout('components.layouts.owner');
    }
}

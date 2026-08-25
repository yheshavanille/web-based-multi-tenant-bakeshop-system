<?php

namespace App\Livewire\Owner\Employees;

use App\Models\Branch;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class ManageEmployees extends Component
{
    public $employees = [];
    public $branches = [];
    public $showForm = false;
    public $editing = false;
    public $employeeId;
    public $name = '';
    public $email = '';
    public $role = '';
    public $branch_id = '';
    public $selectedBranchId = null;
    public $password = '';
    public $password_confirmation = '';
    public $showDeleted = false;
    public $search = '';

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
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

        // Apply search filter
        if (!empty($this->search)) {
            $searchTerm = '%' . $this->search . '%';
            $query->whereHas('user', function ($q) use ($searchTerm) {
                $q->where('name', 'like', $searchTerm)
                    ->orWhere('email', 'like', $searchTerm);
            });
        }

        $this->employees = $query->get();
    }

    public function updatedSearch()
    {
        $this->loadEmployees();
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
        $this->reset(['name', 'email', 'role', 'branch_id', 'password', 'password_confirmation', 'employeeId']);
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

        if (!$employee->user->is_active) {
            session()->flash('error', 'This employee cannot be edited because their account has been suspended.');
            return;
        }

        $this->employeeId = $employee->id;
        $this->name = $employee->user->name;
        $this->email = $employee->user->email;
        $this->role = $employee->role;
        $this->branch_id = $employee->branch_id;
        $this->editing = true;
        $this->showForm = true;
        $this->password = '';
        $this->password_confirmation = '';
    }

    public function cancel()
    {
        $this->showForm = false;
        $this->reset(['name', 'email', 'role', 'branch_id', 'password', 'password_confirmation', 'employeeId']);
    }

    public function save()
    {
        $shop = Auth::user()->shop;

        if ($this->editing) {
            $employee = Employee::findOrFail($this->employeeId);

            if (!$employee->user || $employee->user->trashed()) {
                session()->flash('error', 'This employee cannot be edited because their account has been deleted.');
                return;
            }

            if (!$employee->user->is_active) {
                session()->flash('error', 'This employee cannot be edited because their account has been suspended.');
                return;
            }

            $rules = [
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . $employee->user_id,
                'role' => 'required|in:order_manager,inventory_manager',
                'branch_id' => 'required|exists:branches,id',
            ];
            $this->validate($rules);

            $user = User::findOrFail($employee->user_id);

            $user->update([
                'name' => $this->name,
                'email' => $this->email,
            ]);

            $employee->update([
                'role' => $this->role,
                'branch_id' => $this->branch_id,
            ]);

            session()->flash('message', 'Employee updated successfully!');
        } else {
            $this->validate();

            $user = User::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make($this->password),
                'role' => 'employee',
                'shop_id' => $shop->id,
            ]);

            Employee::create([
                'user_id' => $user->id,
                'shop_id' => $shop->id,
                'branch_id' => $this->branch_id,
                'role' => $this->role,
                'is_active' => true,
            ]);

            session()->flash('message', 'Employee created successfully! Password: ' . $this->password);
        }

        $this->cancel();
        $this->loadEmployees();
    }

    public function toggleStatus($employeeId)
    {
        $employee = Employee::findOrFail($employeeId);

        if (!$employee->user || $employee->user->trashed()) {
            session()->flash('error', 'Cannot change status - user account is deleted.');
            return;
        }

        if (!$employee->user->is_active) {
            session()->flash('error', 'Cannot change status - user account is suspended.');
            return;
        }

        $employee->update(['is_active' => !$employee->is_active]);
        $employee->user->update(['is_active' => $employee->is_active]);
        $this->loadEmployees();
        session()->flash('message', 'Employee status updated.');
    }

    public function delete($employeeId)
    {
        $employee = Employee::findOrFail($employeeId);
        $employeeName = $employee->user?->name ?? 'Unknown Employee';

        // Soft delete the employee only
        $employee->delete();

        $this->loadEmployees();
        session()->flash('message', 'Employee "' . $employeeName . '" deleted successfully.');
    }

    public function restore($employeeId)
    {
        $employee = Employee::onlyTrashed()->findOrFail($employeeId);

        // Check if user still exists and is not deleted
        if (!$employee->user || $employee->user->trashed()) {
            session()->flash('error', 'Cannot restore - the user account has been deleted by Super Admin.');
            return;
        }

        // Check if user is suspended
        if (!$employee->user->is_active) {
            session()->flash('error', 'Cannot restore - the user account is suspended. Please contact Super Admin.');
            return;
        }

        // Restore the employee only
        $employee->restore();

        $this->loadEmployees();
        session()->flash('message', 'Employee "' . $employee->user->name . '" restored successfully.');
    }

    public function render()
    {
        return view('livewire.owner.employees.manage-employees')
            ->layout('components.layouts.owner');
    }
}

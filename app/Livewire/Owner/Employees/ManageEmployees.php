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
        $query = Employee::with(['user', 'branch'])
            ->where('shop_id', $shop->id);

        if ($this->selectedBranchId) {
            $query->where('branch_id', $this->selectedBranchId);
        }

        $this->employees = $query->get();
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
            $rules = [
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . $this->employeeId,
                'role' => 'required|in:order_manager,inventory_manager',
                'branch_id' => 'required|exists:branches,id',
            ];
            $this->validate($rules);

            $employee = Employee::findOrFail($this->employeeId);
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
            ]);

            $user->assignRole('employee');

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
        $employee->update(['is_active' => !$employee->is_active]);
        $employee->user->update(['is_active' => $employee->is_active]);
        $this->loadEmployees();
        session()->flash('message', 'Employee status updated.');
    }

    public function delete($employeeId)
    {
        $employee = Employee::findOrFail($employeeId);
        $user = User::findOrFail($employee->user_id);
        $employee->delete();
        $user->delete();
        $this->loadEmployees();
        session()->flash('message', 'Employee deleted successfully.');
    }

    public function render()
    {
        return view('livewire.owner.employees.manage-employees')
            ->layout('components.layouts.owner');
    }
}

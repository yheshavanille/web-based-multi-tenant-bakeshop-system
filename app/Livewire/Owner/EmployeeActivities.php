<?php

namespace App\Livewire\Owner;

use App\Models\Employee;
use App\Models\EmployeeActivity;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class EmployeeActivities extends Component
{
    public $activities = [];
    public $employees = [];

    // Filters
    public $selectedEmployee = 'all';
    public $selectedAction = 'all';
    public $search = '';

    public function mount()
    {
        $shop = Auth::user()->shop;
        $this->employees = Employee::where('shop_id', $shop->id)
            ->with('user')
            ->get();

        $this->loadActivities();
    }

    public function loadActivities()
    {
        $shop = Auth::user()->shop;

        $query = EmployeeActivity::where('shop_id', $shop->id)
            ->with(['employee.user', 'employee.branch'])
            ->orderBy('created_at', 'desc');

        if ($this->selectedEmployee !== 'all') {
            $query->where('employee_id', $this->selectedEmployee);
        }

        if ($this->selectedAction !== 'all') {
            $query->where('action', $this->selectedAction);
        }

        if (!empty($this->search)) {
            $searchTerm = '%' . $this->search . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->where('description', 'like', $searchTerm);
            });
        }

        $this->activities = $query->get();
    }

    public function updatedSelectedEmployee()
    {
        $this->loadActivities();
    }

    public function updatedSelectedAction()
    {
        $this->loadActivities();
    }

    public function updatedSearch()
    {
        $this->loadActivities();
    }

    public function clearSearch()
    {
        $this->search = '';
        $this->loadActivities();
    }

    public function clearFilters()
    {
        $this->selectedEmployee = 'all';
        $this->selectedAction = 'all';
        $this->search = '';
        $this->loadActivities();
    }

    public function render()
    {
        return view('livewire.owner.employee-activities')
            ->layout('components.layouts.owner');
    }
}

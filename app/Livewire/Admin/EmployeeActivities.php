<?php

namespace App\Livewire\Admin;

use App\Models\Employee;
use App\Models\EmployeeActivity;
use App\Models\Shop;
use Livewire\Component;

class EmployeeActivities extends Component
{
    public $activities = [];
    public $employees = [];
    public $shops = [];

    // Filters
    public $selectedShop = 'all';
    public $selectedEmployee = 'all';
    public $selectedAction = 'all';
    public $search = '';

    public function mount()
    {
        $this->shops = Shop::orderBy('shop_name')->get();
        $this->employees = Employee::with('user')->get();

        // ✅ Auto-filter by shop if the URL has ?shop=ID
        if (request()->has('shop')) {
            $this->selectedShop = (string) request()->get('shop');
        }

        $this->loadActivities();
    }

    public function loadActivities()
    {
        $query = EmployeeActivity::with(['employee.user', 'employee.branch', 'shop'])
            ->orderBy('created_at', 'desc');

        if ($this->selectedShop !== 'all') {
            $query->where('shop_id', $this->selectedShop);
        }

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

    public function updatedSelectedShop()
    {
        $this->loadActivities();
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
        $this->selectedShop = 'all';
        $this->selectedEmployee = 'all';
        $this->selectedAction = 'all';
        $this->search = '';
        $this->loadActivities();
    }

    public function render()
    {
        return view('livewire.admin.employee-activities')
            ->layout('components.layouts.admin');
    }
}

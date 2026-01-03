<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use App\Models\UserAddress;
use App\Models\District;
use App\Models\Province;
use App\Models\Department;
use Illuminate\Support\Facades\Auth;

class ClientAddresses extends Component
{
    public $addresses;
    
    // Selects Data
    public $departments = [];
    public $provinces = [];
    public $districts = [];

    // Form Inputs
    public $addressId;
    public $name; // Alias
    public $address;
    public $reference;
    public $is_default = false;

    // Location State
    public $selectedDepartment = null;
    public $selectedProvince = null;
    public $district_id = null;

    public $isModalOpen = false;

    protected $rules = [
        'name' => 'required|string|max:50',
        'address' => 'required|string|max:255',
        'reference' => 'nullable|string|max:255',
        'district_id' => 'required|exists:districts,id',
    ];

    public function mount()
    {
        $this->departments = Department::orderBy('name')->get();
        $this->loadAddresses();
    }

    public function updatedSelectedDepartment($value)
    {
        $this->provinces = Province::where('department_id', $value)->orderBy('name')->get();
        $this->districts = [];
        $this->selectedProvince = null;
        $this->district_id = null;
    }

    public function updatedSelectedProvince($value)
    {
        $this->districts = District::where('province_id', $value)->orderBy('name')->get();
        $this->district_id = null;
    }

    public function loadAddresses()
    {
        $this->addresses = UserAddress::where('user_id', Auth::id())
            ->with('district.province.department') // Eager load for display
            ->orderBy('is_default', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function create()
    {
        $this->reset(['addressId', 'name', 'address', 'reference', 'district_id', 'is_default', 'selectedDepartment', 'selectedProvince', 'provinces', 'districts']);
        $this->isModalOpen = true;
    }

    public function edit($id)
    {
        $address = UserAddress::where('user_id', Auth::id())->with('district.province')->find($id);
        
        if (!$address) return;

        $this->addressId = $address->id;
        $this->name = $address->name;
        $this->address = $address->address;
        $this->reference = $address->reference;
        $this->is_default = $address->is_default;
        
        // Load Location Data
        if ($address->district) {
            $this->selectedDepartment = $address->district->department_id;
            $this->updatedSelectedDepartment($this->selectedDepartment);
            
            $this->selectedProvince = $address->district->province_id;
            $this->updatedSelectedProvince($this->selectedProvince);

            $this->district_id = $address->district_id;
        }

        $this->isModalOpen = true;
    }

    public function save()
    {
        $this->validate();

        if ($this->is_default) {
            // Uncheck other defaults
            UserAddress::where('user_id', Auth::id())->update(['is_default' => false]);
        }

        UserAddress::updateOrCreate(
            ['id' => $this->addressId],
            [
                'user_id' => Auth::id(),
                'name' => $this->name,
                'address' => $this->address,
                'reference' => $this->reference,
                'district_id' => $this->district_id,
                'is_default' => $this->is_default,
            ]
        );

        $this->isModalOpen = false;
        $this->loadAddresses();
        session()->flash('message', $this->addressId ? 'Dirección actualizada.' : 'Dirección agregada correctamente.');
    }

    public function delete($id)
    {
        $address = UserAddress::where('user_id', Auth::id())->find($id);
        if ($address) {
            $address->delete();
            $this->loadAddresses();
        }
    }

    public function render()
    {
        return view('livewire.dashboard.client-addresses')->layout('components.layouts.app');
    }
}

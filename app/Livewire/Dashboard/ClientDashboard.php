<?php

namespace App\Livewire\Dashboard;

use App\Models\Pet;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class ClientDashboard extends Component
{
    public function mount()
    {
        // Si es veterinario o paseador, redirigir a su dashboard específico
        // (A menos que quieran actuar como dueños, pero por ahora simplificamos)
        if (Auth::user()->hasRole(['veterinarian', 'walker'])) {
            return redirect()->route('dashboard.provider');
        }
    }

    public function render()
    {
        $pets = Pet::where('user_id', Auth::id())->get();

        return view('livewire.dashboard.client-dashboard', [
            'pets' => $pets
        ])->layout('components.layouts.app');
    }
}

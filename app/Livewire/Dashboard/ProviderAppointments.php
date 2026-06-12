<?php

namespace App\Livewire\Dashboard;

use App\Models\Appointment;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class ProviderAppointments extends Component
{
    use WithPagination;

    public $filterStatus = 'pending';
    public $confirmingCancel = null;

    public function confirm($appointmentId)
    {
        $appointment = Appointment::where('provider_id', Auth::id())->findOrFail($appointmentId);
        $appointment->update(['status' => 'confirmed']);
        session()->flash('message', 'Cita confirmada.');
    }

    public function cancel($appointmentId)
    {
        $appointment = Appointment::where('provider_id', Auth::id())->findOrFail($appointmentId);
        $appointment->update(['status' => 'cancelled']);
        $this->confirmingCancel = null;
        session()->flash('message', 'Cita cancelada.');
    }

    public function complete($appointmentId)
    {
        $appointment = Appointment::where('provider_id', Auth::id())->findOrFail($appointmentId);
        $appointment->update(['status' => 'completed']);
        session()->flash('message', 'Cita marcada como completada.');
    }

    public function render()
    {
        $appointments = Appointment::with(['client', 'pet'])
            ->where('provider_id', Auth::id())
            ->when($this->filterStatus !== 'all', fn($q) => $q->where('status', $this->filterStatus))
            ->orderByDesc('scheduled_at')
            ->paginate(10);

        $counts = [
            'pending'   => Appointment::where('provider_id', Auth::id())->where('status', 'pending')->count(),
            'confirmed' => Appointment::where('provider_id', Auth::id())->where('status', 'confirmed')->count(),
            'completed' => Appointment::where('provider_id', Auth::id())->where('status', 'completed')->count(),
            'cancelled'  => Appointment::where('provider_id', Auth::id())->where('status', 'cancelled')->count(),
        ];

        return view('livewire.dashboard.provider-appointments', compact('appointments', 'counts'))
            ->layout('components.layouts.app');
    }
}

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
        
        // Notificar al cliente
        $appointment->client->notify(new \App\Notifications\AppointmentStatusChanged($appointment));
        
        session()->flash('message', 'Cita confirmada.');
    }

    public function cancel($appointmentId)
    {
        $appointment = Appointment::where('provider_id', Auth::id())->findOrFail($appointmentId);
        $appointment->update(['status' => 'cancelled']);
        $this->confirmingCancel = null;

        // Notificar al cliente
        $appointment->client->notify(new \App\Notifications\AppointmentStatusChanged($appointment));

        session()->flash('message', 'Cita cancelada.');
    }

    public function complete($appointmentId)
    {
        $appointment = Appointment::where('provider_id', Auth::id())->findOrFail($appointmentId);
        $appointment->update(['status' => 'completed']);

        // Notificar al cliente
        $appointment->client->notify(new \App\Notifications\AppointmentStatusChanged($appointment));

        session()->flash('message', 'Cita marcada como completada.');
    }

    public function approvePayment($appointmentId)
    {
        $appointment = Appointment::where('provider_id', Auth::id())->findOrFail($appointmentId);
        if ($appointment->payment && $appointment->payment->status === 'under_review') {
            $appointment->payment->update(['status' => 'completed']);
            session()->flash('message', 'El pago ha sido verificado y aprobado con éxito.');
        } else {
            session()->flash('error', 'No se puede aprobar el pago para esta cita o ya está aprobado.');
        }
    }

    public function render()
    {
        $appointments = Appointment::with(['client', 'pet', 'payment'])
            ->where('provider_id', Auth::id())
            ->when($this->filterStatus !== 'all', function ($q) {
                if ($this->filterStatus === 'payment_pending') {
                    return $q->whereIn('status', ['confirmed', 'completed'])
                             ->whereHas('payment', function($qp) {
                                 $qp->whereIn('status', ['pending', 'failed']);
                             });
                }
                if ($this->filterStatus === 'payment_under_review') {
                    return $q->whereHas('payment', function($qp) {
                        $qp->where('status', 'under_review');
                    });
                }
                return $q->where('status', $this->filterStatus);
            })
            ->orderByDesc('scheduled_at')
            ->paginate(10);

        $countsQuery = Appointment::where('provider_id', Auth::id());
        $counts = [
            'all'                  => (clone $countsQuery)->count(),
            'pending'              => (clone $countsQuery)->where('status', 'pending')->count(),
            'confirmed'            => (clone $countsQuery)->where('status', 'confirmed')->count(),
            'completed'            => (clone $countsQuery)->where('status', 'completed')->count(),
            'cancelled'            => (clone $countsQuery)->where('status', 'cancelled')->count(),
            'payment_pending'      => (clone $countsQuery)->whereIn('status', ['confirmed', 'completed'])
                                                         ->whereHas('payment', function($qp) {
                                                             $qp->whereIn('status', ['pending', 'failed']);
                                                         })->count(),
            'payment_under_review' => (clone $countsQuery)->whereHas('payment', function($qp) {
                                                             $qp->where('status', 'under_review');
                                                         })->count(),
        ];

        return view('livewire.dashboard.provider-appointments', compact('appointments', 'counts'))
            ->layout('components.layouts.app');
    }
}

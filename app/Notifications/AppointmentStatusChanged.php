<?php

namespace App\Notifications;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class AppointmentStatusChanged extends Notification
{
    use Queueable;

    public $appointment;

    public function __construct(Appointment $appointment)
    {
        $this->appointment = $appointment;
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        $statusMap = [
            'pending' => 'pendiente',
            'confirmed' => 'confirmada',
            'completed' => 'completada',
            'rejected' => 'rechazada',
            'cancelled' => 'cancelada',
        ];

        $statusTranslated = $statusMap[$this->appointment->status] ?? $this->appointment->status;

        return [
            'appointment_id' => $this->appointment->id,
            'provider_id' => $this->appointment->provider_id,
            'provider_name' => $this->appointment->provider->name,
            'scheduled_at' => $this->appointment->scheduled_at,
            'status' => $this->appointment->status,
            'type' => 'appointment_status_changed',
            'title' => 'Tu cita con ' . $this->appointment->provider->name . ' está ' . $statusTranslated,
        ];
    }
}

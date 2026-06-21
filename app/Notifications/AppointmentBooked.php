<?php

namespace App\Notifications;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class AppointmentBooked extends Notification
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
        return [
            'appointment_id' => $this->appointment->id,
            'client_id' => $this->appointment->client_id,
            'client_name' => $this->appointment->client->name,
            'scheduled_at' => $this->appointment->scheduled_at,
            'type' => 'appointment_booked',
            'title' => 'Nueva cita solicitada por ' . $this->appointment->client->name,
        ];
    }
}

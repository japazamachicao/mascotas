<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class NotificationBell extends Component
{
    protected $listeners = [
        'messages-read' => '$refresh',
        'echo:notifications,NotificationSent' => '$refresh', // Soporte para broadcasting opcional
    ];

    public function markAllAsRead()
    {
        if (Auth::check()) {
            Auth::user()->unreadNotifications->markAsRead();
            $this->dispatch('notifications-updated');
        }
    }

    public function handleNotificationClick($notificationId)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $notification = Auth::user()->notifications()->find($notificationId);
        
        if ($notification) {
            $notification->markAsRead();
            
            $data = $notification->data;
            $type = $data['type'] ?? '';

            if ($type === 'chat_message') {
                return redirect()->route('dashboard.messages', ['contactId' => $data['sender_id'] ?? null]);
            }

            if ($type === 'appointment_booked') {
                return redirect()->route('dashboard.provider.appointments');
            }

            if ($type === 'appointment_status_changed') {
                // Si el usuario es proveedor, redirige a citas del proveedor, si no, a citas de cliente
                $user = Auth::user();
                if ($user->hasAnyRole(['veterinarian', 'walker', 'groomer', 'hotel', 'shelter', 'trainer', 'pet_sitter', 'pet_taxi', 'pet_photographer'])) {
                    return redirect()->route('dashboard.provider.appointments');
                }
                return redirect()->route('dashboard.appointments');
            }
        }

        return redirect()->route('dashboard');
    }

    public function render()
    {
        $notifications = collect();
        $unreadCount = 0;

        if (Auth::check()) {
            $unreadCount = Auth::user()->unreadNotifications()->count();
            $notifications = Auth::user()->unreadNotifications()->latest()->take(5)->get();
        }

        return view('livewire.dashboard.notification-bell', [
            'notifications' => $notifications,
            'unreadCount' => $unreadCount,
        ]);
    }
}

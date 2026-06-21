<?php

namespace App\Livewire\Dashboard;

use App\Models\BlockedDate;
use App\Models\Appointment;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class VisualCalendar extends Component
{
    public $weekOffset = 0;
    
    // Modal properties
    public $showBlockModal = false;
    public $selectedDate = null;
    public $blockNotes = '';

    public function goToPreviousWeek()
    {
        $this->weekOffset--;
    }

    public function goToNextWeek()
    {
        $this->weekOffset++;
    }

    public function goToCurrentWeek()
    {
        $this->weekOffset = 0;
    }

    public function openBlockModal($dateString)
    {
        $this->selectedDate = $dateString;
        $this->blockNotes = '';
        $this->showBlockModal = true;
    }

    public function closeBlockModal()
    {
        $this->showBlockModal = false;
        $this->selectedDate = null;
        $this->blockNotes = '';
    }

    public function blockDate()
    {
        $this->validate([
            'selectedDate' => 'required|date',
            'blockNotes' => 'nullable|string|max:255',
        ]);

        $providerId = Auth::id();

        // Evitar duplicados
        BlockedDate::firstOrCreate(
            [
                'provider_id' => $providerId,
                'blocked_date' => $this->selectedDate,
            ],
            [
                'notes' => $this->blockNotes,
            ]
        );

        $this->closeBlockModal();
        session()->flash('message', 'Fecha bloqueada correctamente.');
    }

    public function unblockDate($dateString)
    {
        $providerId = Auth::id();

        BlockedDate::where('provider_id', $providerId)
            ->whereDate('blocked_date', $dateString)
            ->delete();

        session()->flash('message', 'Fecha desbloqueada correctamente.');
    }

    public function render()
    {
        $providerId = Auth::id();
        
        // Calcular inicio y fin de la semana basándonos en el offset
        $weekStart = Carbon::now()->startOfWeek(Carbon::MONDAY)->addWeeks($this->weekOffset);
        $weekEnd = $weekStart->copy()->endOfWeek(Carbon::SUNDAY);

        // Horas a mostrar en el calendario
        $hours = [
            '08:00', '09:00', '10:00', '11:00', '12:00', '13:00', 
            '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00'
        ];

        // Obtener fechas bloqueadas de la semana
        $blockedDates = BlockedDate::where('provider_id', $providerId)
            ->whereBetween('blocked_date', [$weekStart->toDateString(), $weekEnd->toDateString()])
            ->get()
            ->keyBy(function ($item) {
                return Carbon::parse($item->blocked_date)->format('Y-m-d');
            });

        // Obtener citas de la semana (pending o confirmed)
        $appointments = Appointment::where('provider_id', $providerId)
            ->whereIn('status', ['pending', 'confirmed', 'completed'])
            ->whereBetween('scheduled_at', [$weekStart->copy()->startOfDay()->toDateTimeString(), $weekEnd->copy()->endOfDay()->toDateTimeString()])
            ->with(['client', 'pet', 'services'])
            ->get();

        // Generar los 7 días de la semana con su información
        $daysOfWeek = [];
        for ($i = 0; $i < 7; $i++) {
            $currentDay = $weekStart->copy()->addDays($i);
            $dayStr = $currentDay->format('Y-m-d');
            
            // Citas del día
            $dayAppointments = $appointments->filter(function ($app) use ($dayStr) {
                return Carbon::parse($app->scheduled_at)->format('Y-m-d') === $dayStr;
            });

            // Agrupar citas por hora para facilitar la renderización en el slot correspondiente
            $appointmentsByHour = [];
            foreach ($dayAppointments as $app) {
                $hour = Carbon::parse($app->scheduled_at)->format('H') . ':00';
                $appointmentsByHour[$hour][] = $app;
            }

            $daysOfWeek[] = [
                'date' => $currentDay,
                'formatted' => $dayStr,
                'name' => $currentDay->translatedFormat('l'),
                'short_name' => $currentDay->translatedFormat('D'),
                'day_num' => $currentDay->format('d'),
                'is_today' => $currentDay->isToday(),
                'is_blocked' => isset($blockedDates[$dayStr]),
                'block_record' => $blockedDates[$dayStr] ?? null,
                'appointments_by_hour' => $appointmentsByHour,
                'total_appointments' => $dayAppointments->count(),
            ];
        }

        return view('livewire.dashboard.visual-calendar', [
            'daysOfWeek' => $daysOfWeek,
            'hours' => $hours,
            'weekStart' => $weekStart,
            'weekEnd' => $weekEnd,
        ]);
    }
}

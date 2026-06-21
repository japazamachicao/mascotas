<?php

namespace App\Livewire\Dashboard;

use App\Models\Appointment;
use App\Models\Payment;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ClientAppointments extends Component
{
    use WithPagination;
    use WithFileUploads;

    public $filterStatus = 'all';

    // Payment Modal State
    public $showPaymentModal = false;
    public $selectedAppointmentId = null;
    public $selectedAppointment = null;
    public $paymentMethod = 'culqi'; // 'culqi', 'yape', 'plin'
    public $receiptPhoto;
    public $operationCode = '';

    // Livewire listeners
    protected $listeners = [
        'culqiPaymentSuccess' => 'processCulqiPayment'
    ];

    public function cancelAppointment($appointmentId)
    {
        $appointment = Appointment::where('client_id', Auth::id())->findOrFail($appointmentId);

        // Solo permitir cancelar si está pendiente o confirmada
        if (in_array($appointment->status, ['pending', 'confirmed'])) {
            $appointment->update(['status' => 'cancelled']);

            // Notificar al proveedor
            $appointment->provider->notify(new \App\Notifications\AppointmentStatusChanged($appointment));

            session()->flash('message', 'Cita cancelada con éxito.');
        } else {
            session()->flash('error', 'No puedes cancelar esta cita.');
        }
    }

    public function openPaymentModal($appointmentId)
    {
        $this->selectedAppointmentId = $appointmentId;
        $this->selectedAppointment = Appointment::with(['provider', 'payment', 'pet'])->findOrFail($appointmentId);
        $this->paymentMethod = 'culqi';
        $this->receiptPhoto = null;
        $this->operationCode = '';
        $this->showPaymentModal = true;

        $this->dispatch('openCulqiCheckout', [
            'amount' => intval(round($this->selectedAppointment->payment->amount * 100)),
            'email' => Auth::user()->email,
            'description' => 'Servicio TodoPeludos #' . $this->selectedAppointment->id,
            'appointmentId' => $this->selectedAppointmentId
        ]);
    }

    public function processCulqiPayment($data)
    {
        $token = $data['token'];
        $email = $data['email'];
        $appointmentId = $data['appointmentId'] ?? $this->selectedAppointmentId;

        $appointment = Appointment::with('payment')->findOrFail($appointmentId);
        $payment = $appointment->payment;

        if (!$payment) {
            session()->flash('error', 'No se encontró un registro de pago para esta cita.');
            return;
        }

        try {
            $culqi = new \Culqi\Culqi([
                'api_key' => config('services.culqi.secret_key')
            ]);

            $charge = $culqi->Charges->create([
                'amount' => intval(round($payment->amount * 100)),
                'currency_code' => 'PEN',
                'email' => $email,
                'source_id' => $token,
                'description' => 'Pago de cita #' . $appointment->id
            ]);

            if (is_object($charge) && isset($charge->id)) {
                $payment->update([
                    'payment_method' => 'culqi',
                    'status' => 'completed',
                    'transaction_reference' => $charge->id,
                ]);
                $this->showPaymentModal = false;
                session()->flash('message', '¡Pago procesado con éxito vía tarjeta de crédito/débito!');
            } else {
                $payment->update([
                    'status' => 'failed',
                ]);
                session()->flash('error', 'El cargo no pudo ser completado.');
            }
        } catch (\Exception $e) {
            Log::error('Culqi error: ' . $e->getMessage());
            $payment->update([
                'status' => 'failed',
                'transaction_reference' => substr($e->getMessage(), 0, 255),
            ]);
            session()->flash('error', 'Error en pasarela: ' . $e->getMessage());
        }
    }

    public function submitManualPayment()
    {
        $this->validate([
            'paymentMethod' => 'required|in:yape,plin',
            'receiptPhoto' => 'required|image|max:10240', // 10MB
            'operationCode' => 'nullable|string|max:50',
        ], [
            'receiptPhoto.required' => 'Debes subir la foto del comprobante de transferencia.',
            'receiptPhoto.image' => 'El archivo debe ser una imagen.',
            'receiptPhoto.max' => 'La imagen no debe pesar más de 10MB.',
        ]);

        $appointment = Appointment::with('payment')->findOrFail($this->selectedAppointmentId);
        $payment = $appointment->payment;

        if (!$payment) {
            session()->flash('error', 'No se encontró un registro de pago.');
            return;
        }

        $path = $this->receiptPhoto->store('receipts', config('filesystems.default'));

        $payment->update([
            'payment_method' => $this->paymentMethod,
            'status' => 'under_review',
            'transaction_reference' => $this->operationCode ?: null,
            'receipt_photo_path' => $path,
        ]);

        $this->showPaymentModal = false;
        session()->flash('message', 'Comprobante de pago subido. El proveedor lo revisará pronto.');
    }

    public function render()
    {
        $appointments = Appointment::with([
            'provider.roles',
            'provider.veterinarianProfile',
            'provider.walkerProfile',
            'provider.groomerProfile',
            'provider.hotelProfile',
            'provider.shelterProfile',
            'provider.trainerProfile',
            'provider.petSitterProfile',
            'provider.petTaxiProfile',
            'provider.petPhotographerProfile',
            'pet',
            'payment'
        ])
        ->where('client_id', Auth::id())
        ->when($this->filterStatus !== 'all', function ($q) {
            return $q->where('status', $this->filterStatus);
        })
        ->orderByDesc('scheduled_at')
        ->paginate(10);

        $counts = [
            'all'       => Appointment::where('client_id', Auth::id())->count(),
            'pending'   => Appointment::where('client_id', Auth::id())->where('status', 'pending')->count(),
            'confirmed' => Appointment::where('client_id', Auth::id())->where('status', 'confirmed')->count(),
            'completed' => Appointment::where('client_id', Auth::id())->where('status', 'completed')->count(),
            'cancelled' => Appointment::where('client_id', Auth::id())->where('status', 'cancelled')->count(),
        ];

        return view('livewire.dashboard.client-appointments', compact('appointments', 'counts'))
            ->layout('components.layouts.app');
    }
}

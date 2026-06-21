<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Password;
use Livewire\Component;

class ForgotPassword extends Component
{
    public $email = '';
    public $status = '';

    protected $rules = [
        'email' => 'required|email',
    ];

    public function sendResetLink()
    {
        $this->validate();

        // Enviar el enlace de recuperación
        $response = Password::broker()->sendResetLink(
            ['email' => $this->email]
        );

        if ($response == Password::RESET_LINK_SENT) {
            $this->status = __('Hemos enviado por correo el enlace para restablecer tu contraseña.');
            $this->reset('email');
        } else {
            $this->addError('email', __($response));
        }
    }

    public function render()
    {
        return view('livewire.auth.forgot-password')->layout('components.layouts.app', ['title' => 'Recuperar Contraseña']);
    }
}

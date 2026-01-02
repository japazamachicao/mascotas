<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Login extends Component
{
    public $email = '';
    public $password = '';
    public $remember = false;

    protected $rules = [
        'email' => 'required|email',
        'password' => 'required',
    ];

    public function login()
    {
        $this->validate();

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            request()->session()->regenerate();

            return redirect()->intended(route('dashboard'));
        }

        $this->addError('email', 'Estas credenciales no coinciden con nuestros registros.');
    }

    public function render()
    {
        return view('livewire.auth.login')->layout('components.layouts.app', ['title' => 'Iniciar Sesión']);
    }
}

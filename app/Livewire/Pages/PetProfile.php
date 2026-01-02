<?php

namespace App\Livewire\Pages;

use App\Models\Pet;
use Livewire\Component;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class PetProfile extends Component
{
    public $pet;

    public function mount($uuid)
    {
        $this->pet = Pet::where('uuid', $uuid)->firstOrFail();
    }

    public function render()
    {
        // Generar el QR al vuelo para mostrarlo en la página
        $url = route('pet.profile', ['uuid' => $this->pet->uuid]);
        $qrCode = QrCode::size(200)->generate($url);

        return view('livewire.pages.pet-profile', [
            'qrCode' => $qrCode
        ])->layout('components.layouts.app', ['title' => $this->pet->name]);
    }
}

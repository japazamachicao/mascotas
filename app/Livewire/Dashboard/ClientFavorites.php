<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class ClientFavorites extends Component
{
    public $favorites;

    public function mount()
    {
        $this->loadFavorites();
    }

    public function loadFavorites()
    {
        $this->favorites = Auth::user()->favoriteProviders()->get();
    }

    public function removeFavorite($providerId)
    {
        Auth::user()->favoriteProviders()->detach($providerId);
        $this->loadFavorites();
        session()->flash('message', 'Proveedor eliminado de favoritos.');
    }

    public function render()
    {
        return view('livewire.dashboard.client-favorites');
    }
}

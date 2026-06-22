<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class Settings extends Component
{
    use WithFileUploads;

    public $logo;

    protected $rules = [
        'logo' => 'image|max:2048|mimes:png,jpg,jpeg,svg,webp',
    ];

    public function subirLogo(): void
    {
        $this->validate();

        Storage::disk('public')->makeDirectory('logo');
        $this->logo->storeAs('logo', 'logo.png', 'public');

        session()->flash('success', 'Logo actualizado correctamente.');
        $this->logo = null;
    }

    public function eliminarLogo(): void
    {
        Storage::disk('public')->delete('logo/logo.png');
        session()->flash('success', 'Logo eliminado. Se usará el logo por defecto.');
    }

    public function render()
    {
        return view('livewire.settings', [
            'tieneLogoPersonalizado' => Storage::disk('public')->exists('logo/logo.png'),
        ]);
    }
}

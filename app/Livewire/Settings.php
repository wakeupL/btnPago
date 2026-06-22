<?php

namespace App\Livewire;

use App\Services\AppSettings;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class Settings extends Component
{
    use WithFileUploads;

    public $logo;
    public string $correoNotificaciones = '';

    public function mount(): void
    {
        $this->correoNotificaciones = AppSettings::get('correo_notificaciones', config('mail.from.address', ''));
    }

    protected function rules(): array
    {
        return [
            'logo'                  => 'nullable|image|max:2048|mimes:png,jpg,jpeg,svg,webp',
            'correoNotificaciones'  => 'required|email|max:255',
        ];
    }

    public function subirLogo(): void
    {
        $this->validateOnly('logo');

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

    public function guardarCorreo(): void
    {
        $this->validateOnly('correoNotificaciones');

        AppSettings::set('correo_notificaciones', $this->correoNotificaciones);

        session()->flash('success_correo', 'Correo de notificaciones actualizado.');
    }

    public function render()
    {
        return view('livewire.settings', [
            'tieneLogoPersonalizado' => Storage::disk('public')->exists('logo/logo.png'),
        ]);
    }
}

<?php

namespace App\Http\Livewire;

use Livewire\Component;

class CenteredCard extends Component
{
    public $title;
    public $content;
    public $isVisible = false; // Inicialmente oculto

    public function render()
    {
        return view('livewire.centered-card');
    }
}
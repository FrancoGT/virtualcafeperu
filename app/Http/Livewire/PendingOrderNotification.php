<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;

class PendingOrderNotification extends Component
{
    public $hasPendingOrders = false;

    public function mount()
    {
        $this->checkPendingOrders();
    }

    public function checkPendingOrders()
    {
        // Obtener el ID del usuario autenticado
        $userId = Auth::check() ? Auth::id() : config('app.anonymous_user_id');

        // Verificar si el usuario tiene órdenes pendientes
        $this->hasPendingOrders = Order::where('user_id', $userId)
            ->where('status', 'pending')
            ->exists();
    }

    public function render()
    {
        return view('livewire.pending-order-notification');
    }
}
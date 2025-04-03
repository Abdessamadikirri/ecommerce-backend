<?php

namespace App\Listeners;

use App\Events\userLoggedIn;
use App\Models\Cart;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CreateCartForUser
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(userLoggedIn $event): void
    {
        $user = $event->user;

        // Check if the user already has a cart
        if (!$user->cart) {
            Cart::create([
                'user_id' => $user->id,
                // 'status' =>'active',
            ]);
        }
    }
}

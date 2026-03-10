<?php

namespace App\Listeners;

use App\Mail\WelcomeEmail;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendWelcomeEmail implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Create the event listener.
     */
    public function __construct() {}

    /**
     * Handle the event.
     */
    public function handle(Registered $event): void
    {
        try {
            // Cast to User model and send welcome email
            if ($event->user instanceof User) {
                Mail::to($event->user->email)->send(new WelcomeEmail($event->user));
            }
        } catch (\Exception $error) {
            \Illuminate\Support\Facades\Log::warning(
                'Erreur envoi email bienvenue pour ' . $event->user->email . ': ' . $error->getMessage()
            );
            // Ne pas relancer l'exception - l'utilisateur doit pouvoir se créer un compte même si l'email échoue
        }
    }
}

<?php

namespace App\Listeners;

use App\Events\NewUserRegistered;
use App\Mail\UserCreatedMail;
use Illuminate\Support\Facades\Mail;

class SendWelcomeEmail
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
    public function handle(NewUserRegistered $event): void
    {
        Mail::to($event->user->email)->send(new UserCreatedMail($event->user));
    }
}

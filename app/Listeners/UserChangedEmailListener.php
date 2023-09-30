<?php

namespace App\Listeners;

use App\Events\UserChangedMailEvent;
use App\Mail\UserMailChanged;
use Illuminate\Support\Facades\Mail;

class UserChangedEmailListener
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
    public function handle(UserChangedMailEvent $event): void
    {
        if ($event->user->isDirty(['email'])) {
            Mail::to($event->user->email)->send(new UserMailChanged($event->user));
        }
    }
}

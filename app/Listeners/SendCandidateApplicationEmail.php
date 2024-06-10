<?php

namespace App\Listeners;

use App\Mail\ApplicationConfirmationMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendCandidateApplicationEmail
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
    public function handle(object $event): void
    {
        // dd($event);
        $candidate = $event->candidate;

        Mail::to($candidate->email)->send(new ApplicationConfirmationMail($candidate));
    }
}

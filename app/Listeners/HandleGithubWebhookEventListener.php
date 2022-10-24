<?php

namespace App\Listeners;

use App\Events\ExampleEvent;
use App\Events\GitHubWebHookEvent;
use App\Mails\PushNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class HandleGithubWebhookEventListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\ExampleEvent  $event
     * @return void
     */
    public function handle(GitHubWebHookEvent $event)
    {
        \Log::info($event->event->getType());
        \Log::info($event->event->getPayload());

        app('mailer')->send(new PushNotification($event->event, $event->project));
    }
}

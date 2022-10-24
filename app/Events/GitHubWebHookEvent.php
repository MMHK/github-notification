<?php

namespace App\Events;

use \Swop\GitHubWebHook\Event\GitHubEvent;

class GitHubWebHookEvent extends Event
{
    /**
     * @var GitHubEvent
     */
    public $event;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(GitHubEvent $event)
    {
        $this->event = $event;
    }
}

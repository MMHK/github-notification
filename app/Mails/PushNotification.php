<?php


namespace App\Mails;


use Illuminate\Mail\Mailable;
use Swop\GitHubWebHook\Event\GitHubEvent;

class PushNotification extends Mailable
{
    use ToManyMailTrait;

    /**
     * @var GitHubEvent
     */
    protected $event;
    protected $project;
    /**
     * PushNotification constructor.
     */
    public function __construct(GitHubEvent $event, $project)
    {
        $this->event = $event;
        $this->project = $project;
    }

    public function build()
    {
        $toList = config('services.github.webhook.projects-email.'.$this->project, []);
        $this->to($toList);

        $payload = $this->event->getPayload();
        $full_name = array_get($payload, 'repository.full_name');
        $html_url = array_get($payload, 'repository.html_url');
        $last_msg = array_get($payload, 'head_commit.message');

        $this->subject(sprintf('Project [%s] has been updated', $full_name));

        return $this->html(sprintf('Dear Developers:<br><br>Your project <a href="%s" target="_blank">%s</a> has been updated. <br><br>Commit Message: %s',
            $html_url, $full_name, $last_msg));
    }
}

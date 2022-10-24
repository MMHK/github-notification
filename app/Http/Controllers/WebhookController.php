<?php


namespace App\Http\Controllers;


use App\Events\GitHubWebHookEvent;
use Psr\Http\Message\ServerRequestInterface;
use Swop\GitHubWebHook\Security\SignatureValidator;
use Swop\GitHubWebHook\Event\GitHubEventFactory;

class WebhookController extends Controller
{

    public function callback($project, ServerRequestInterface $request) {
        \Log::info($project);
        \Log::info($request->getBody());
        \Log::info($request->getHeaders());

        $validator = new SignatureValidator();
        $secret = config('services.github.webhook.projects-secret.'.$project, '');

        try {
            if ($validator->validate($request, $secret)) {
                // Request is correctly signed
                $factory = new GitHubEventFactory();
                event(new GitHubWebHookEvent($factory->buildFromRequest($request), $project));
            }
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            \Log::error($e->getTraceAsString());
        }

        return [
            true,
        ];
    }
}

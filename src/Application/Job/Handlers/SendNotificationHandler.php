<?php

namespace Zahirzohair\Neuracore\Application\Job\Handlers;

use Zahirzohair\Neuracore\Domain\Job\Job;

class SendNotificationHandler implements JobHandler
{
    public function handle(Job $job): void
    {
        $payload = $job->payload();

        // simulate work
        echo "Sending notification for workflow {$payload['workflow_id']}\n";
    }
}

<?php

namespace Zahirzohair\Neuracore\Application\Job;

use Zahirzohair\Neuracore\Domain\Job\Job;
use Zahirzohair\Neuracore\Domain\Job\JobRepository;

class JobService
{
    private array $handlers;
    private $jobs;

    public function __construct(JobRepository $jobs, array $handlers)
    {
        $this->jobs = $jobs;
        $this->handlers = $handlers;
    }

    public function dispatch(string $type, array $payload): Job
    {
        $job = new Job(null, $type, $payload);
        return $this->jobs->save($job);
    }

    public function processNext(): void
    {
        $job = $this->jobs->claimNextPending();
        if (!$job) return;

        try {
            $handler = $this->handlers[$job->type()] ?? null;

            if (!$handler) {
                throw new \RuntimeException("No handler for {$job->type()}");
            }

            $handler->handle($job);

            $this->jobs->markCompleted($job->id());
        } catch (\Throwable $e) {
            $nextStatus = ($job->attempts() < $job->maxAttempts()) ? 'pending' : 'failed';
            $this->jobs->markFailed($job->id(), $nextStatus, $e->getMessage());
        }
    }

    public function recentForUser(int $userId, int $limit = 50): array
    {
        $jobs = $this->jobs->recent($limit);

        return array_values(array_filter($jobs, function (Job $job) use ($userId) {
            $payload = $job->payload();
            return isset($payload['user_id']) && (int)$payload['user_id'] === $userId;
        }));
    }
}

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
        $job = $this->jobs->nextPending();
        if (!$job) return;

        $job->markProcessing();
        $this->jobs->updateStatus($job->id(), $job->status());

        try {
            $handler = $this->handlers[$job->type()] ?? null;

            if (!$handler) {
                throw new \RuntimeException("No handler for {$job->type()}");
            }

            $handler->handle($job);

            $job->markCompleted();
            $this->jobs->updateStatus($job->id(), $job->status());
        } catch (\Throwable $e) {
            $job->markFailed();
            $this->jobs->updateStatus($job->id(), $job->status());
        }
    }
}

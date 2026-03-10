<?php

namespace Zahirzohair\Neuracore\Domain\Job;

interface JobRepository
{
    public function save(Job $job): Job;
    public function claimNextPending(): ?Job;
    public function markCompleted(int $id): void;
    public function markFailed(int $id, string $status, string $error): void;
    public function recent(int $limit = 50): array;
}

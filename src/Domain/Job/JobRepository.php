<?php

namespace Zahirzohair\Neuracore\Domain\Job;

interface JobRepository
{
    public function save(Job $job): Job;
    public function nextPending(): ?Job;
    public function updateStatus(int $id, string $status): void;
}

<?php

namespace Zahirzohair\Neuracore\Domain\Job;

class Job
{
    private ?int $id;
    private string $type;
    private array $payload;
    private string $status;
    private int $attempts;
    private int $maxAttempts;
    private ?string $lastError;

    public function __construct(
        ?int $id,
        string $type,
        array $payload,
        string $status = 'pending',
        int $attempts = 0,
        int $maxAttempts = 3,
        ?string $lastError = null
    ) {
        $this->id = $id;
        $this->type = $type;
        $this->payload = $payload;
        $this->status = $status;
        $this->attempts = $attempts;
        $this->maxAttempts = $maxAttempts;
        $this->lastError = $lastError;
    }

    public function id(): ?int
    {
        return $this->id;
    }
    public function type(): string
    {
        return $this->type;
    }
    public function payload(): array
    {
        return $this->payload;
    }
    public function status(): string
    {
        return $this->status;
    }

    public function attempts(): int
    {
        return $this->attempts;
    }

    public function maxAttempts(): int
    {
        return $this->maxAttempts;
    }

    public function lastError(): ?string
    {
        return $this->lastError;
    }

    public function markProcessing(): void
    {
        $this->status = 'processing';
    }

    public function markCompleted(): void
    {
        $this->status = 'completed';
    }

    public function markFailed(): void
    {
        $this->status = 'failed';
    }
}

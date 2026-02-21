<?php

namespace Zahirzohair\Neuracore\Domain\Job;

class Job
{
    private ?int $id;
    private string $type;
    private array $payload;
    private string $status;

    public function __construct(
        ?int $id,
        string $type,
        array $payload,
        string $status = 'pending'
    ) {
        $this->id = $id;
        $this->type = $type;
        $this->payload = $payload;
        $this->status = $status;
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

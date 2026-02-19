<?php

namespace Zahirzohair\Neuracore\Domain\Workflow;

class Workflow
{
    private ?int $id;
    private string $name;
    private string $status;
    private int $userId;
    private array $steps;

    public function __construct(
        ?int $id,
        string $name,
        int $userId,
        array $steps = [],
        string $status = 'pending'
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->userId = $userId;
        $this->steps = $steps;
        $this->status = $status;
    }

    public function id(): ?int
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function userId(): int
    {
        return $this->userId;
    }

    public function status(): string
    {
        return $this->status;
    }

    public function steps(): array
    {
        return $this->steps;
    }

    public function markRunning(): void
    {
        $this->status = 'running';
    }

    public function markCompleted(): void
    {
        $this->status = 'completed';
    }
}

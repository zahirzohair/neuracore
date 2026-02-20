<?php

namespace Zahirzohair\Neuracore\Domain\Event;

class Event
{
    private ?int $id;
    private string $name;
    private array $payload;
    private \DateTimeImmutable $occurredAt;

    public function __construct(
        ?int $id,
        string $name,
        array $payload = [],
        ?\DateTimeImmutable $occurredAt = null
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->payload = $payload;
        $this->occurredAt = $occurredAt ?? new \DateTimeImmutable();
    }

    public function id(): ?int
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function payload(): array
    {
        return $this->payload;
    }

    public function occurredAt(): \DateTimeImmutable
    {
        return $this->occurredAt;
    }
}

<?php

namespace Zahirzohair\Neuracore\Domain\Event;

interface EventRepository
{
    public function save(Event $event): Event;

    public function all(): array;

    public function recent(int $limit = 100): array;
}

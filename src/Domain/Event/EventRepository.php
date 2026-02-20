<?php

namespace Zahirzohair\Neuracore\Domain\Event;

interface EventRepository
{
    public function save(Event $event): Event;

    public function all(): array;
}

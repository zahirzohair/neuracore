<?php

namespace Zahirzohair\Neuracore\Core;

use Zahirzohair\Neuracore\Domain\Event\Event;

class EventDispatcher
{
    private array $listeners = [];

    public function listen(string $eventName, callable $listener): void
    {
        $this->listeners[$eventName][] = $listener;
    }

    public function dispatch(Event $event): void
    {
        $listeners = $this->listeners[$event->name()] ?? [];

        foreach ($listeners as $listener) {
            $listener($event);
        }
    }
}

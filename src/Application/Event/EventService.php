<?php

namespace Zahirzohair\Neuracore\Application\Event;

use Zahirzohair\Neuracore\Domain\Event\Event;
use Zahirzohair\Neuracore\Domain\Event\EventRepository;
use Zahirzohair\Neuracore\Core\EventDispatcher;

class EventService
{
    private EventRepository $events;
    private EventDispatcher $dispatcher;

    public function __construct(EventRepository $events, EventDispatcher $dispatcher)
    {
        $this->events = $events;
        $this->dispatcher = $dispatcher;
    }

    public function fire(string $name, array $payload = []): Event
    {
        $event = new Event(null, $name, $payload);

        $saved = $this->events->save($event);

        $this->dispatcher->dispatch($saved);

        return $saved;
    }

    public function all(): array
    {
        return $this->events->all();
    }

    public function recentForUser(int $userId, int $limit = 100): array
    {
        $events = $this->events->recent($limit);

        return array_values(array_filter($events, function (Event $event) use ($userId) {
            $payload = $event->payload();
            return isset($payload['user_id']) && (int)$payload['user_id'] === $userId;
        }));
    }
}

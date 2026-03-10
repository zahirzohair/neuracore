<?php

namespace Zahirzohair\Neuracore\Infrastructure\Persistence;

use PDO;
use Zahirzohair\Neuracore\Domain\Event\Event;
use Zahirzohair\Neuracore\Domain\Event\EventRepository;

class MySQLEventRepository implements EventRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function save(Event $event): Event
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO events (name, payload, occurred_at)
            VALUES (?, ?, ?)
        ");

        $stmt->execute([
            $event->name(),
            json_encode($event->payload()),
            $event->occurredAt()->format('Y-m-d H:i:s')
        ]);

        $id = (int)$this->pdo->lastInsertId();

        return new Event(
            $id,
            $event->name(),
            $event->payload(),
            $event->occurredAt()
        );
    }

    public function all(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM events ORDER BY id DESC");
        $rows = $stmt->fetchAll();

        return array_map(fn($row) => new Event(
            (int)$row['id'],
            $row['name'],
            json_decode($row['payload'], true),
            new \DateTimeImmutable($row['occurred_at'])
        ), $rows);
    }

    public function recent(int $limit = 100): array
    {
        $limit = max(1, min(500, (int)$limit));
        $stmt = $this->pdo->query("SELECT * FROM events ORDER BY id DESC LIMIT {$limit}");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map(fn($row) => new Event(
            (int)$row['id'],
            $row['name'],
            json_decode($row['payload'], true) ?? [],
            new \DateTimeImmutable($row['occurred_at'])
        ), $rows);
    }
}

<?php

namespace Zahirzohair\Neuracore\Infrastructure\Persistence;

use PDO;
use Zahirzohair\Neuracore\Domain\Job\Job;
use Zahirzohair\Neuracore\Domain\Job\JobRepository;

class MySQLJobRepository implements JobRepository
{
    public function __construct(private PDO $pdo) {}

    public function save(Job $job): Job
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO jobs (type, payload, status) VALUES (?, ?, ?)"
        );

        $stmt->execute([
            $job->type(),
            json_encode($job->payload()),
            $job->status()
        ]);

        return new Job(
            (int) $this->pdo->lastInsertId(),
            $job->type(),
            $job->payload(),
            $job->status()
        );
    }

    public function nextPending(): ?Job
    {
        $stmt = $this->pdo->query(
            "SELECT * FROM jobs WHERE status='pending' ORDER BY id ASC LIMIT 1"
        );

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) return null;

        return new Job(
            (int)$row['id'],
            $row['type'],
            json_decode($row['payload'], true),
            $row['status']
        );
    }

    public function updateStatus(int $id, string $status): void
    {
        $stmt = $this->pdo->prepare(
            "UPDATE jobs SET status=? WHERE id=?"
        );

        $stmt->execute([$status, $id]);
    }
}

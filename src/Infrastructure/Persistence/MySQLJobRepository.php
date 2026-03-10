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
            "INSERT INTO jobs (type, payload, status, attempts, max_attempts) VALUES (?, ?, ?, ?, ?)"
        );

        $stmt->execute([
            $job->type(),
            json_encode($job->payload()),
            $job->status(),
            $job->attempts(),
            $job->maxAttempts(),
        ]);

        return new Job(
            (int) $this->pdo->lastInsertId(),
            $job->type(),
            $job->payload(),
            $job->status(),
            $job->attempts(),
            $job->maxAttempts(),
            $job->lastError()
        );
    }

    public function claimNextPending(): ?Job
    {
        $this->pdo->beginTransaction();
        try {
            $stmt = $this->pdo->query(
                "SELECT * FROM jobs
                 WHERE status='pending' AND attempts < max_attempts
                 ORDER BY id ASC
                 LIMIT 1
                 FOR UPDATE"
            );

            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$row) {
                $this->pdo->commit();
                return null;
            }

            $id = (int) $row['id'];
            $attempts = ((int) $row['attempts']) + 1;
            $maxAttempts = (int) ($row['max_attempts'] ?? 3);

            $update = $this->pdo->prepare(
                "UPDATE jobs
                 SET status='processing',
                     attempts=?,
                     last_error=NULL,
                     processing_at=NOW(),
                     completed_at=NULL,
                     failed_at=NULL
                 WHERE id=?"
            );
            $update->execute([$attempts, $id]);

            $this->pdo->commit();

            return new Job(
                $id,
                $row['type'],
                json_decode($row['payload'], true) ?? [],
                'processing',
                $attempts,
                $maxAttempts,
                null
            );
        } catch (\Throwable $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            throw $e;
        }
    }

    public function markCompleted(int $id): void
    {
        $stmt = $this->pdo->prepare(
            "UPDATE jobs SET status='completed', completed_at=NOW() WHERE id=?"
        );

        $stmt->execute([$id]);
    }

    public function markFailed(int $id, string $status, string $error): void
    {
        if ($status !== 'pending' && $status !== 'failed') {
            throw new \InvalidArgumentException('Invalid failure status');
        }

        if ($status === 'pending') {
            $stmt = $this->pdo->prepare(
                "UPDATE jobs
                 SET status='pending',
                     last_error=?,
                     processing_at=NULL
                 WHERE id=?"
            );
            $stmt->execute([$error, $id]);
            return;
        }

        $stmt = $this->pdo->prepare(
            "UPDATE jobs
             SET status='failed',
                 last_error=?,
                 failed_at=NOW()
             WHERE id=?"
        );
        $stmt->execute([$error, $id]);
    }

    public function recent(int $limit = 50): array
    {
        $limit = max(1, min(200, (int)$limit));

        $stmt = $this->pdo->query("SELECT * FROM jobs ORDER BY id DESC LIMIT {$limit}");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map(function (array $row) {
            return new Job(
                (int)$row['id'],
                $row['type'],
                json_decode($row['payload'], true) ?? [],
                $row['status'],
                (int)($row['attempts'] ?? 0),
                (int)($row['max_attempts'] ?? 3),
                $row['last_error'] ?? null
            );
        }, $rows);
    }
}

<?php

namespace Zahirzohair\Neuracore\Infrastructure\Persistence;

use PDO;
use Zahirzohair\Neuracore\Domain\Workflow\Workflow;
use Zahirzohair\Neuracore\Domain\Workflow\WorkflowRepository;

class MySQLWorkflowRepository implements WorkflowRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function save(Workflow $workflow): Workflow
    {
        if ($workflow->id()) {
            $stmt = $this->pdo->prepare("
                UPDATE workflows
                SET name = ?, status = ?, steps = ?
                WHERE id = ?
            ");

            $stmt->execute([
                $workflow->name(),
                $workflow->status(),
                json_encode($workflow->steps()),
                $workflow->id()
            ]);

            return $workflow;
        }

        $stmt = $this->pdo->prepare("
            INSERT INTO workflows (name, user_id, status, steps)
            VALUES (?, ?, ?, ?)
        ");

        $stmt->execute([
            $workflow->name(),
            $workflow->userId(),
            $workflow->status(),
            json_encode($workflow->steps())
        ]);

        $id = (int) $this->pdo->lastInsertId();

        return new Workflow(
            $id,
            $workflow->name(),
            $workflow->userId(),
            $workflow->steps(),
            $workflow->status()
        );
    }

    public function findById(int $id): ?Workflow
    {
        $stmt = $this->pdo->prepare("SELECT * FROM workflows WHERE id = ?");
        $stmt->execute([$id]);

        $row = $stmt->fetch();

        if (!$row) return null;

        return new Workflow(
            (int)$row['id'],
            $row['name'],
            (int)$row['user_id'],
            json_decode($row['steps'], true),
            $row['status']
        );
    }

    public function findByUser(int $userId): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM workflows WHERE user_id = ?");
        $stmt->execute([$userId]);

        $rows = $stmt->fetchAll();

        return array_map(fn($row) => new Workflow(
            (int)$row['id'],
            $row['name'],
            (int)$row['user_id'],
            json_decode($row['steps'], true),
            $row['status']
        ), $rows);
    }

    public function all(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM workflows");

        $rows = $stmt->fetchAll();

        return array_map(fn($row) => new Workflow(
            (int)$row['id'],
            $row['name'],
            (int)$row['user_id'],
            json_decode($row['steps'], true),
            $row['status']
        ), $rows);
    }
}

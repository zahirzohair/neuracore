<?php

namespace Zahirzohair\Neuracore\Domain\Workflow;

interface WorkflowRepository
{
    public function save(Workflow $workflow): Workflow;

    public function findById(int $id): ?Workflow;

    public function findByUser(int $userId): array;

    public function all(): array;
}

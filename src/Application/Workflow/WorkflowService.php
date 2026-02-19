<?php

namespace Zahirzohair\Neuracore\Application\Workflow;

use Zahirzohair\Neuracore\Domain\Workflow\Workflow;
use Zahirzohair\Neuracore\Domain\Workflow\WorkflowRepository;

class WorkflowService
{
    private WorkflowRepository $workflows;

    public function __construct(WorkflowRepository $workflows)
    {
        $this->workflows = $workflows;
    }

    public function create(string $name, int $userId, array $steps): Workflow
    {
        $workflow = new Workflow(
            null,
            $name,
            $userId,
            $steps
        );

        return $this->workflows->save($workflow);
    }

    public function start(int $workflowId): ?Workflow
    {
        $workflow = $this->workflows->findById($workflowId);

        if (!$workflow) {
            return null;
        }

        $workflow->markRunning();

        return $this->workflows->save($workflow);
    }

    public function complete(int $workflowId): ?Workflow
    {
        $workflow = $this->workflows->findById($workflowId);

        if (!$workflow) {
            return null;
        }

        $workflow->markCompleted();

        return $this->workflows->save($workflow);
    }

    public function forUser(int $userId): array
    {
        return $this->workflows->findByUser($userId);
    }

    public function all(): array
    {
        return $this->workflows->all();
    }
}

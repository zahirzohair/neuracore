<?php

namespace Zahirzohair\Neuracore\Application\Workflow;

use Zahirzohair\Neuracore\Application\Event\EventService;
use Zahirzohair\Neuracore\Domain\Workflow\Workflow;
use Zahirzohair\Neuracore\Domain\Workflow\WorkflowRepository;

class WorkflowService
{
    private WorkflowRepository $workflows;
    private EventService $eventService;

    public function __construct(WorkflowRepository $workflows, EventService $eventService)
    {
        $this->workflows = $workflows;
        $this->eventService = $eventService;
    }

    public function create(string $name, int $userId, array $steps): Workflow
    {
        $workflow = new Workflow(
            null,
            $name,
            $userId,
            $steps
        );

        $savedWorkflow = $this->workflows->save($workflow);
        // 🔥 Fire event
        $this->eventService->fire('workflow.created', [
            'workflow_id' => $savedWorkflow->id(),
            'user_id' => $userId,
            'name' => $savedWorkflow->name()
        ]);

        return $savedWorkflow;
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

<?php

namespace Zahirzohair\Neuracore\Controllers;

use Zahirzohair\Neuracore\Application\Workflow\WorkflowService;
use Zahirzohair\Neuracore\Core\Auth;
use Zahirzohair\Neuracore\Core\Request;
use Zahirzohair\Neuracore\Core\Response;
use Zahirzohair\Neuracore\Core\View;

class WorkflowController
{
    private WorkflowService $workflowService;

    public function __construct(WorkflowService $workflowService)
    {
        $this->workflowService = $workflowService;
    }

    public function index(Request $request)
    {
        Auth::requireLogin();
        $userId = Auth::id();

        $workflows = $this->workflowService->forUser($userId);

        $html = View::render('workflows.index', [
            'workflows' => $workflows,
        ]);

        Response::html($html);
    }

    public function create(Request $request)
    {
        Auth::requireLogin();
        $userId = Auth::id();

        $name = trim((string)$request->input('name', ''));
        if ($name === '') {
            return Response::html('Workflow name is required', 422);
        }

        $steps = [];

        $stepsJson = $request->input('steps_json');
        if (is_string($stepsJson) && trim($stepsJson) !== '') {
            $decoded = json_decode($stepsJson, true);
            if (!is_array($decoded)) {
                return Response::html('Invalid steps JSON. Expected a JSON array.', 422);
            }
            $steps = $decoded;
        } else {
            $stepsInput = $request->input('steps', []);
            if (is_array($stepsInput)) {
                $steps = array_values(array_filter(array_map(
                    fn($s) => is_string($s) ? trim($s) : '',
                    $stepsInput
                ), fn($s) => $s !== ''));
            }
        }

        $workflow = $this->workflowService->create(
            $name,
            $userId,
            $steps
        );

        Response::redirect('/workflows');
    }

    public function start(Request $request)
    {
        Auth::requireLogin();
        $userId = Auth::id();

        $workflow = $this->workflowService->startForUser((int)$request->input('id'), $userId);

        if (!$workflow) {
            return Response::html('Workflow not found', 404);
        }

        Response::html("Workflow started: {$workflow->id()}");
    }

    public function complete(Request $request)
    {
        Auth::requireLogin();
        $userId = Auth::id();

        $workflow = $this->workflowService->completeForUser((int)$request->input('id'), $userId);

        if (!$workflow) {
            return Response::html('Workflow not found', 404);
        }

        Response::html("Workflow completed: {$workflow->id()}");
    }
}

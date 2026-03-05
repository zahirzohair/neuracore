<?php

namespace Zahirzohair\Neuracore\Controllers;

use Zahirzohair\Neuracore\Application\Workflow\WorkflowService;
use Zahirzohair\Neuracore\Core\Request;
use Zahirzohair\Neuracore\Core\Response;

class WorkflowController
{
    private WorkflowService $workflowService;

    public function __construct(WorkflowService $workflowService)
    {
        $this->workflowService = $workflowService;
    }

    public function index()
    {
        $workflows = $this->workflowService->all();

        foreach ($workflows as $wf) {
            echo "ID: {$wf->id()} | {$wf->name()} | {$wf->status()} <br>";
        }
    }

    public function create(Request $request)
    {
        $workflow = $this->workflowService->create(
            $request->input('name'),
            1, // user id (temporary)
            ['step1', 'step2']
        );

        Response::html("Workflow created with ID: " . $workflow->id());
    }

    public function start(Request $request)
    {
        $workflow = $this->workflowService->start((int)$request->input('id'));

        if (!$workflow) {
            return Response::html('Workflow not found', 404);
        }

        Response::html("Workflow started: {$workflow->id()}");
    }

    public function complete(Request $request)
    {
        $workflow = $this->workflowService->complete((int)$request->input('id'));

        if (!$workflow) {
            return Response::html('Workflow not found', 404);
        }

        Response::html("Workflow completed: {$workflow->id()}");
    }
}

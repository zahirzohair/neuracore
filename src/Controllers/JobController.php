<?php

namespace Zahirzohair\Neuracore\Controllers;

use Zahirzohair\Neuracore\Application\Job\JobService;
use Zahirzohair\Neuracore\Core\Auth;
use Zahirzohair\Neuracore\Core\Request;
use Zahirzohair\Neuracore\Core\Response;
use Zahirzohair\Neuracore\Core\View;

class JobController
{
    public function __construct(private JobService $jobService) {}

    public function index(Request $request): void
    {
        Auth::requireLogin();
        $userId = Auth::id();

        $jobs = $this->jobService->recentForUser($userId, 100);
        $html = View::render('jobs.index', ['jobs' => $jobs]);

        Response::html($html);
    }
}


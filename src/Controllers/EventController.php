<?php

namespace Zahirzohair\Neuracore\Controllers;

use Zahirzohair\Neuracore\Application\Event\EventService;
use Zahirzohair\Neuracore\Core\Auth;
use Zahirzohair\Neuracore\Core\Request;
use Zahirzohair\Neuracore\Core\Response;
use Zahirzohair\Neuracore\Core\View;

class EventController
{
    public function __construct(private EventService $eventService) {}

    public function index(Request $request): void
    {
        Auth::requireLogin();
        $userId = Auth::id();

        $events = $this->eventService->recentForUser($userId, 200);
        $html = View::render('events.index', ['events' => $events]);

        Response::html($html);
    }
}


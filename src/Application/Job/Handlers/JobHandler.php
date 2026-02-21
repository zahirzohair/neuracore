<?php

namespace Zahirzohair\Neuracore\Application\Job\Handlers;

use Zahirzohair\Neuracore\Domain\Job\Job;

interface JobHandler
{
    public function handle(Job $job): void;
}

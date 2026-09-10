<?php

namespace App\Events;

use App\Models\TechnicalTask;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TechnicalTaskAssigned
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public TechnicalTask $task
    ) {}
}

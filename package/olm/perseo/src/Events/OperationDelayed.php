<?php

namespace Olm\Perseo\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;

class OperationDelayed extends Event {
    use SerializesModels;

    public function __construct(Operation $operation)
    {
        
    }
}
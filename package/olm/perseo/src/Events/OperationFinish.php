<?php

namespace Olm\Perseo\Events;

use App\Events\Event;
use Olm\Perseo\Operation;
use Illuminate\Queue\SerializesModels;

class OperationFinish extends Event {
    use SerializesModels;

    public function __construct(Operation $operation)
    {
        
    }
}
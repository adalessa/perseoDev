<?php

namespace Olm\Perseo\Jobs;

use Olm\Perseo\Jobs\Job;
use Olm\Perseo\Implementation\Operation;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;

class ProcessScheduledOperation extends Job implements ShouldQueue, SelfHandling
{
    use InteractsWithQueue, SerializesModels;
    protected $operation;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Operation $operation)
    {
        $this->operation = $operation;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->operation->run();
    }
}

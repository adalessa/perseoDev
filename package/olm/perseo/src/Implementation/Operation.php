<?php 
namespace Olm\Perseo\Implementation;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Olm\Perseo\Jobs\ProcessScheduledOperation;
use Olm\Perseo\Exceptions\OperationWorkflowPathInvalid;
use Olm\Perseo\Contracts\Operation as OperationContract;

abstract class Operation implements OperationContract
{
    use DispatchesJobs;

    protected $id;
    protected $input;
    protected $output;
    protected $result;
    protected $queue;
    protected $nextOperation = null;
    protected $workflow = null;
    protected $callbackFunction = null;

    public function run()
    {
        //this function will execute the process method in the operations
        $result = $this->process();
        
        if ($result instanceof OperationContract) {
            return;
        }

        $this->setResult($result);
        $this->runCallback();
        $next = $this->checkNext();
        if ($next !== null) {
            $next->setInput($this->output());
            $next->run();
            $result = $next->result();
            if ($result instanceof OperationContract) {
                return;
            }
            $this->setOutput($next->output());
            $this->setResult($result);
        }
    }

    private function runCallback()
    {
        if ($this->callbackFunction !== null) {
            if (! is_callable($this->callbackFunction)) {
                throw new \Exception("Not a valid callback", 1);
            }
            $func = $this->callbackFunction;
            $this->setOutput($func($this->output()));
        }
    }

    public function callback(callable $callback)
    {
        $this->callbackFunction = $callback;
        return $this;
    }

    protected function checkNext()
    {
        if ($this->workflow !== null) {
            foreach ($this->workflow as $posiblePath) {
                if ($this->result() === $posiblePath->compare) {
                    return $posiblePath->operation;
                }
            }
        }
        return $this->nextOperation;
    }

    public function then(OperationContract $next)
    {
        $this->nextOperation = $next;
        return $this;
    }

    public function otherwise(OperationContract $next)
    {
        return $this->then($next);
    }

    public function thenIf($compare, OperationContract $next)
    {
        $this->workflow = $this->workflow ? : [];
        $posiblePath = new \stdClass;
        $posiblePath->compare = $compare;
        $posiblePath->operation = $next;
        $this->pushPosiblePath($posiblePath);
        return $this;
    }

    protected function pushPosiblePath($posiblePath)
    {
        foreach ($this->workflow as $path) {
            if ($path->compare === $posiblePath->compare) {
                throw new OperationWorkflowPathInvalid(
                    "The value {$posiblePath->compare} it is already assign",
                    1
                );
            }
        }
        array_push($this->workflow, $posiblePath);
    }

    public function schedule($delay = 0)
    {
        $job = (new ProcessScheduledOperation($this))
                ->onQueue($this->getQueue())
                ->delay($delay);
        $this->dispatch($job);
        return $this;
    }

    public function release($delay)
    {
        return $this->schedule($delay);
    }

    /**
     * Gets the value of id.
     *
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets the value of id.
     *
     * @param mixed $id the id
     *
     * @return self
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Gets the value of input.
     *
     * @return mixed
     */
    public function input()
    {
        return $this->input;
    }

    /**
     * Sets the value of input.
     *
     * @param mixed $input the input
     *
     * @return self
     */
    public function setInput($input)
    {
        $this->input = $input;

        return $this;
    }

    /**
     * Gets the value of output.
     *
     * @return mixed
     */
    public function output()
    {
        return $this->output;
    }

    /**
     * Sets the value of output.
     *
     * @param mixed $output the output
     *
     * @return self
     */
    public function setOutput($output)
    {
        $this->output = $output;

        return $this;
    }

    /**
     * Gets the value of result.
     *
     * @return mixed
     */
    public function result()
    {
        return $this->result;
    }

    /**
     * Sets the value of result.
     *
     * @param mixed $result the result
     *
     * @return self
     */
    public function setResult($result)
    {
        $this->result = $result;

        return $this;
    }

    /**
     * Gets the value of queue.
     *
     * @return mixed
     */
    public function getQueue()
    {
        return $this->queue;
    }

    /**
     * Sets the value of queue.
     *
     * @param mixed $queue the queue
     *
     * @return self
     */
    public function setQueue($queue)
    {
        $this->queue = $queue;

        return $this;
    }
}

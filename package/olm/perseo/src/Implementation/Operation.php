<?php 
namespace Olm\Perseo\Implementation;

use Olm\Perseo\TimeLength;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Olm\Perseo\Jobs\ProcessScheduledOperation;
use Olm\Perseo\Exceptions\OperationWorkflowPathInvalid;

abstract class Operation
{
    use DispatchesJobs;

    protected $id;
    protected $input;
    protected $output;
    protected $processOutput;
    protected $result;
    protected $queue;
    protected $nextOperation = null;
    protected $workflow = null;
    protected $callbackFunction = null;

    
    abstract public function process();
    /**
     *
     * This is the funciton that will manage the excecution of the operation
     *
     */
    public function run()
    {
        $result = $this->process();

        if ($this->hasBeenSchedule($result)) {
            return $this;
        }

        $this->setResult($result);
        $this->runCallback();

        $this->setProcessOutput();

        $this->processNext();
        return $this;
    }

    private function processNext()
    {
        $next = $this->checkNext();
        
        if (is_null($next)) {
            return $this;
        }

        $next->setInput($this->output());
        $next->run();

        $result = $next->result();
        if ($this->hasBeenSchedule($result)) {
            return $this;
        }

        $this->setOutput($next->output());
    }

    protected function hasBeenSchedule($result)
    {
        return $this->isAnOperation($result);
    }

    private function isAnOperation($result)
    {
        return $result instanceof Operation;
    }

    private function runCallback()
    {
        if ($this->validCallbackFunction()) {
            $func = $this->callbackFunction;
            $this->setOutput($func($this->output()));
        }        
    }

    private function validCallbackFunction() {
        if (is_null($this->callbackFunction)) {
            return false;
        }
        if (!is_callable($this->callbackFunction)) {
            throw new \Exception("Not a valid callback", 1);
        }
        return true;
    }

    public function callback(callable $callback)
    {
        $this->callbackFunction = $callback;
        return $this;
    }

    protected function checkNext()
    {
        if (is_null($this->workflow)) {
            return $this->nextOperation;    
        }
        return $this->getNextStep();
        
    }

    private function getNextStep() {
        return array_first($this->workflow , function($posiblePath){
            return $this->result() === $posiblePath->compare;
        });
    }

    public function then(Operation $next)
    {
        $this->nextOperation = $next;
        return $this;
    }

    public function otherwise(Operation $next)
    {
        return $this->then($next);
    }

    public function thenIf($compare, Operation $next)
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
        $element = array_first($this->workflow, function ($path) use ($posiblePath) {
            return $path->compare === $posiblePath->compare;
        });
        if (!is_null($element)) {
            $message = "The value {$posiblePath->compare} it is already assign";
            throw new OperationWorkflowPathInvalid($message, 1);
        }
        array_push($this->workflow, $posiblePath);
    }

    public function schedule(TimeLength $length = null)
    {
        if (is_null($length)) {
            $length = TimeLength::fromSeconds(0);
        }
        
        $job = (new ProcessScheduledOperation($this))
                ->onQueue($this->getQueue())
                ->delay($length->inSeconds());
        $this->dispatch($job);
        return $this;
    }

    public function release(TimeLength $length = null)
    {
        return $this->schedule($length ? : TimeLength::fromSeconds(0));
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
     * Gets the value of input.
     *
     * @return mixed
     */
    public function processInput()
    {
        return $this->input();
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

    /**
     * Gets the value of processOutput.
     *
     * @return mixed
     */
    public function processOutput()
    {
        return $this->processOutput;
    }

    /**
     * Sets the value of processOutput.
     *
     * @param mixed $processOutput the process output
     *
     * @return self
     */
    protected function setProcessOutput()
    {
        $this->processOutput = $this->output;

        return $this;
    }
}

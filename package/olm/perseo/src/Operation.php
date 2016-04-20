<?php 
namespace Olm\Perseo;

use App;
use Olm\Perseo\Exceptions\OperationNotFound;
use Olm\Perseo\Exceptions\OperationNotImplementsContract;
use Olm\Perseo\Contracts\IdGenerator as IdGeneratorContract;
use Olm\Perseo\Implementation\Operation as OperationImplementation;

class Operation
{
    protected $idGenerator;

	/**
		This is using the laravel ioc so the dependencies will be injected automatically
	*/
    public function __construct(IdGeneratorContract $idGenerator)
    {
        $this->idGenerator = $idGenerator;
    }

    public function make($operationName)
    {
        $this->validateOperaitonName($operationName);
        
        $operation = App::make($operationName);
        $this->validateOperation($operation, $operationName);
        $this->setUpOperation($operation);
        return $operation;
    }

    protected function setUpOperation(OperationImplementation $operation)
    {
        $operation->setId($this->idGenerator->get());
    }
    
    private function validateOperaitonName($operationName)
    {
        if (!class_exists($operationName)) {
            throw new OperationNotFound("Class {$operationName} not exists", 1);
        }
    }
    private function validateOperation($operation, $operationName)
    {
        if (! $operation instanceof OperationImplementation) {
            $message = "Class {$operationName} not implements the Interface";
            throw new OperationNotImplementsContract($message, 1);
        }
    }
}

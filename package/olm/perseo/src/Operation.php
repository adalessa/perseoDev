<?php 
namespace Olm\Perseo;

use App;
use Config;
use Olm\Perseo\Exceptions\OperationNotFound;
use Olm\Perseo\Contracts\Operation as OperationContract;
use Olm\Perseo\Exceptions\OperationNotImplementsContract;
use Olm\Perseo\Contracts\IdGenerator as IdGeneratorContract;

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

    protected function setUpOperation(OperationContract $operation)
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
        if (! $operation instanceof OperationContract) {
            $message = "Class {$operationName} not implements the Interface";
            throw new OperationNotImplementsContract($message, 1);
        }
    }
}

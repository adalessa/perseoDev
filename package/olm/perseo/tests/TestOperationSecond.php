<?php 
namespace Olm\PerseoTest;

use Olm\Perseo\Contracts\Operation as OperationContract;
use Olm\Perseo\Implementation\Operation as OperationImplementation;

class TestOperationSecond extends OperationImplementation implements OperationContract
{
    public function process()
    {
    	$data =$this->input();
    	$data["operation"] = "operation2";
    	$this->setOutput($data);
    	return true;
    }
}

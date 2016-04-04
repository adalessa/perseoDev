<?php 
namespace Olm\PerseoTest;

use Olm\Perseo\Contracts\Operation as OperationContract;
use Olm\Perseo\Implementation\Operation as OperationImplementation;

class TestOperationAdd extends OperationImplementation implements OperationContract
{
    public function process()
    {
    	$data = $this->input();
    	$data["c"] = $data["a"] + $data["b"];
    	$this->setOutput($data);
    	return true;
    }
}

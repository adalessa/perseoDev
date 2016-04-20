<?php 
namespace Olm\PerseoTest;

use Olm\Perseo\Implementation\Operation;

class TestOperationSecond extends Operation
{
    public function process()
    {
    	$data =$this->input();
    	$data["operation"] = "operation2";
    	$this->setOutput($data);
    	return true;
    }
}

<?php 
namespace Olm\PerseoTest;

use Olm\Perseo\Implementation\Operation;

class TestOperationThird extends Operation
{
    public function process()
    {
    	$data =$this->input();
    	$data["operation"] = "operation3";
    	$this->setOutput($data);
    	return true;
    }
}

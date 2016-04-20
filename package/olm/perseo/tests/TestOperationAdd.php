<?php 
namespace Olm\PerseoTest;

use Olm\Perseo\Implementation\Operation as Operation;

class TestOperationAdd extends Operation 
{
    public function process()
    {
    	$data = $this->input();
    	$data["c"] = $data["a"] + $data["b"];
    	$this->setOutput($data);
    	return true;
    }
}

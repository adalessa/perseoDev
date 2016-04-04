<?php 

namespace App;

use Olm\Perseo\Contracts\Operation as OperationContract;
use Olm\Perseo\Implementation\Operation as OperationImplementation;

class OtherOperation extends OperationImplementation implements OperationContract
{
    public function process()
    {
    	$data = $this->input();
        $data->otherTest = 'there';
        $data->id[] = $this->getId();
        var_dump($data);
		//return $this->release(10);
        $this->setOutput($data);
        return true;
    }
}

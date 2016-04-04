<?php 
namespace Olm\PerseoTest;

use Olm\Perseo\Contracts\Operation as OperationContract;
use Olm\Perseo\Implementation\Operation as OperationImplementation;

class TestOperationTwo extends OperationImplementation implements OperationContract
{
    public function process()
    {
		$data = $this->input();
        $data['lastname'] = "testTwo";
        $this->setOutput($data);
        return true;
    }
}

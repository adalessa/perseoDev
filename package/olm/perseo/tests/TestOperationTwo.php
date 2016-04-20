<?php 
namespace Olm\PerseoTest;

use Olm\Perseo\Implementation\Operation;

class TestOperationTwo extends Operation
{
    public function process()
    {
		$data = $this->input();
        $data['lastname'] = "testTwo";
        $this->setOutput($data);
        return true;
    }
}

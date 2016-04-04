<?php 
namespace Olm\PerseoTest;

use Olm\Perseo\Contracts\Operation as OperationContract;
use Olm\Perseo\Implementation\Operation as OperationImplementation;

class TestOperationOne extends OperationImplementation implements OperationContract
{
    public function process()
    {
        $data = $this->input();
        $data['name'] = "testOne";
        $this->setOutput($data);
        return true;
    }
}

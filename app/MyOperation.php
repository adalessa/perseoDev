<?php 

namespace App;

use Olm\Perseo\Contracts\Operation as OperationContract;
use Olm\Perseo\Implementation\Operation as OperationImplementation;

class MyOperation extends OperationImplementation implements OperationContract
{
    public function process()
    {
        $data = new \stdClass();
        $data->test = 'here';
        $data->id = [$this->getId()];
        $this->setOutput($data);
        return true;
    }
}

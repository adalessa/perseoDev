<?php 

namespace App;

use Olm\Perseo\Contracts\Operation as OperationContract;
use Olm\Perseo\Implementation\Operation as OperationImplementation;

class SomeOperation extends OperationImplementation implements OperationContract
{
    public function process()
    {
        $data = $this->input();
        $data->someTest = 'someWhere';
        $data->id[] = $this->getId();
        var_dump($data);
        $this->setOutput($data);
        return true;
    }
}

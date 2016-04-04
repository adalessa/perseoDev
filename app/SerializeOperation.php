<?php 

namespace App;

use Cache;
use Olm\Perseo\Contracts\Operation as OperationContract;
use Olm\Perseo\Implementation\Operation as OperationImplementation;

class SerializeOperation extends OperationImplementation implements OperationContract
{
    public function process()
    {	
        $a = serialize($this);
        Cache::put('test', $a, 20);
    }
}

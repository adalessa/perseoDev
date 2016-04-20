<?php 
namespace Olm\PerseoTest;

use Olm\Perseo\Implementation\Operation;

class TestOperationOne extends Operation
{
    public function process()
    {
        $data = $this->input();
        $data['name'] = "testOne";
        $this->setOutput($data);
        return true;
    }
}

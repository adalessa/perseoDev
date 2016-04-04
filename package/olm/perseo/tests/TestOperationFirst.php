<?php 
namespace Olm\PerseoTest;

use Olm\Perseo\Traits\ComplexCompareInThenIf;
use Olm\Perseo\Contracts\Operation as OperationContract;
use Olm\Perseo\Implementation\Operation as OperationImplementation;

class TestOperationFirst extends OperationImplementation implements OperationContract
{
    use ComplexCompareInThenIf;

    public function process()
    {
        $data = $this->input();
        $result = $data['check'] == true;
        $this->setOutput($data);
        return $result;
    }

}

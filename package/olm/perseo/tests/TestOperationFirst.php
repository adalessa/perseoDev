<?php 
namespace Olm\PerseoTest;

use Olm\Perseo\Traits\ComplexCompareInThenIf;
use Olm\Perseo\Implementation\Operation;

class TestOperationFirst extends Operation
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

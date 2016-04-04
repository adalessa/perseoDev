<?php

namespace Olm\Perseo\Traits;

use Olm\Perseo\Exceptions\OperationWorkflowPathInvalid;

trait ComplexCompareInThenIf
{
    protected function pushPosiblePath($posiblePath)
    {
        if (! is_callable($posiblePath->compare)) {
            foreach ($this->workflow as $path) {
                if ($path->compare === $posiblePath->compare) {
                    throw new OperationWorkflowPathInvalid(
                    "The value {$posiblePath->compare} it is already assign",
                    1
                );
                }
            }
        }
        array_push($this->workflow, $posiblePath);
    }

    
    protected function checkNext()
    {
        if ($this->workflow !== null) {
            foreach ($this->workflow as $posiblePath) {
                if (is_callable($posiblePath->compare)) {
                    $func = $posiblePath->compare;
                    if ($func($this->output())) {
                        return $posiblePath->operation;
                    }
                } elseif ($this->result() === $posiblePath->compare) {
                    return $posiblePath->operation;
                }
            }
        }
        return $this->nextOperation;
    }
}

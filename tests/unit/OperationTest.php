<?php

use Olm\PerseoTest\TestOperation;
use Olm\PerseoTest\TestOperationAdd;
use Olm\PerseoTest\TestOperationOne;
use Olm\PerseoTest\TestOperationTwo;
use Olm\PerseoTest\TestOperationFirst;
use Olm\PerseoTest\TestOperationThird;
use Olm\PerseoTest\TestOperationSecond;
use Olm\Perseo\Exceptions\OperationNotFound;
use Olm\PerseoTest\TestOperationNotImplements;
use Olm\Perseo\Jobs\ProcessScheduledOperation;
use Olm\Perseo\Exceptions\OperationWorkflowPathInvalid;
use Olm\Perseo\Exceptions\OperationNotImplementsContract;

class OperationTest extends TestCase
{
    /** @test */
    public function can_create_a_operation()
    {
        $operation = Operation::make(TestOperation::class);

        $this->assertEquals(TestOperation::class, get_class($operation));
    }

    /** @test */
    public function throw_exception_if_not_class_not_exit()
    {
        $this->setExpectedException(OperationNotFound::class);
        Operation::make('NotValidClass');
    }

    /** @test */
    public function trhow_exception_if_the_class_not_implement_the_contract()
    {
        $this->setExpectedException(OperationNotImplementsContract::class);
        Operation::make(TestOperationNotImplements::class);
    }

    /** @test */
    public function a_opertion_has_a_unique_id()
    {
        $operation1 = Operation::make(TestOperation::class);
        $operation2 = Operation::make(TestOperation::class);
        $this->assertNotEquals($operation1->getId(), $operation2->getId());
    }

    /** @test */
    public function a_operation_can_process_data()
    {
        $operation1 = Operation::make(TestOperationAdd::class);

        $operation1->setInput(["a" => 1, "b" => 2])->run();

        $this->assertArraySubset(["c" => 3], $operation1->output());
        $this->assertEquals(true, $operation1->result());
    }

    /** @test */
    public function a_operation_can_be_schedule()
    {
        $this->expectsJobs(ProcessScheduledOperation::class);
        $operation = Operation::make(TestOperation::class);
        $operation->schedule();
    }

    /** @test */
    public function operations_can_be_concat()
    {
        $operation1 = Operation::make(TestOperationOne::class);
        $operation2 = Operation::make(TestOperationTwo::class);

        $operation1->then($operation2);
        $operation1->run();
        $this->assertEquals(
            ["name" => "testOne", "lastname" => "testTwo"],
            $operation1->output()
        );
    }

    /** @test */
    public function operation_can_have_multiple_paths()
    {
        $operation1 = Operation::make(TestOperationFirst::class);
        $operation2 = Operation::make(TestOperationSecond::class);
        $operation3 = Operation::make(TestOperationThird::class);

        $operation1->thenIf(true, $operation2)
                   ->thenIf(false, $operation3);

        $operation1->setInput(["check" => true]);
        $operation1->run();
        $this->assertArraySubset(["operation" => "operation2"], $operation1->output());

        $operation1->setInput(["check" => false]);
        $operation1->run();
        $this->assertArraySubset(["operation" => "operation3"], $operation1->output());
    }

    /** @test */
    public function operation_can_have_path_validated_by_a_function()
    {
        $operation1 = Operation::make(TestOperationFirst::class);
        $operation2 = Operation::make(TestOperationSecond::class);
        $operation3 = Operation::make(TestOperationThird::class);

        $operation1->thenIf(function() {return false;}, $operation2)
                   ->thenIf(function() {return true;}, $operation3);
        $operation1->run();
        
        $this->assertArraySubset(["operation" => "operation3"], $operation1->output());
    }

    /** @test */
    public function operation_cant_have_same_value_to_comapre_in_the_then_if()
    {
        $this->setExpectedException(OperationWorkflowPathInvalid::class);

        $operation1 = Operation::make(TestOperationFirst::class);
        $operation2 = Operation::make(TestOperationSecond::class);
        $operation3 = Operation::make(TestOperationThird::class);

        $operation1->thenIf(true, $operation2);
        $operation1->thenIf(true, $operation3);
    }

    /** @test */
    public function operation_can_have_a_function_callback()
    {
        $operation = Operation::make(TestOperation::class);
        $callback = function ($data) { $data = []; $data['use_callback'] = true; return $data;};
        $operation->callback($callback);
        $operation->run();
        $this->assertArraySubset(["use_callback" => true], $operation->output());
    }

    /** @test */
    public function operation_has_otherwise_in_case_of_no_path_to_follow()
    {
        $operation1 = Operation::make(TestOperationFirst::class);
        $operation2 = Operation::make(TestOperationSecond::class);
        $operation3 = Operation::make(TestOperationThird::class);

        $operation1->thenIf(true, $operation2)
                   ->otherwise($operation3)
                   ->setInput(["check" => false])
                   ->run();
        $this->assertArraySubset(["operation" => "operation3"], $operation1->output());
    }

    /** @test */
    public function operation_can_run_in_one_line()
    {
        $this->assertEquals(
            ['name' => 'testOne'],
            Operation::make(TestOperationOne::class)->run()->output()
        );

        $operation1 = Operation::make(TestOperationOne::class);
        $operation2 = Operation::make(TestOperationTwo::class);

        $this->assertEquals(
            ["name" => "testOne", "lastname" => "testTwo"],
            $operation1->then($operation2)->run()->output()
        );
    }

    /** @test */
    public function run_a_callback_after_a_then()
    {
        $operation1 = Operation::make(TestOperationOne::class);
        $operation2 = Operation::make(TestOperationTwo::class);

        $callback = function ($data) {$data['use_callback'] = true; return $data;};
        $operation2->callback($callback);
        $operation1->then($operation2)->run();
        
        $this->assertEquals(
            ["name" => "testOne", "lastname" => "testTwo","use_callback" => true],
            $operation1->output()
        );
    }

    /** @test */
    public function each_operation_has_it_owns_data()
    {
        $operation1 = Operation::make(TestOperationOne::class);
        $operation2 = Operation::make(TestOperationTwo::class);
        $operation3 = Operation::make(TestOperationThird::class);
        $operation1->then(
            $operation2->then($operation3)
        )->run();
        
        $this->assertEquals(
            ["name" => "testOne"],
            $operation1->processOutput()
        );
        
        $this->assertEquals(
            ["name" => "testOne", "lastname" => "testTwo"],
            $operation2->processOutput()
        );

        $this->assertEquals(
            ["name" => "testOne", "lastname" => "testTwo","operation" => "operation3"],
            $operation3->output()
        );
    }
}

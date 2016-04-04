<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    Operation::make(App\MyOperation::class)->then(
       	Operation::make(App\OtherOperation::class)->then(
               	Operation::make(App\SomeOperation::class)->thenIf(
                   	Operation::make(App\OneResultOperation::class)
                )->thenIf(
                   Operation::make(App\OtherResultOperation::class)
                )
           )
    )->schedule();
    
});
Route::get('/explain', function () {
    $operation = Operation::make(App\MyOperation::class);
    $operation2 = Operation::make(App\OtherOperation::class);
    $operation3 = Operation::make(App\SomeOperation::class);

    $operation->then($operation2);
    $operation2->then($operation3);

    
    $operation->schedule();
    
    echo $operation->getId() .' - '.$operation2->getId() .' - '.$operation3->getId();
});

Route::get('/ser', function () {
    Operation::make(App\SerializeOperation::class)->run();
});

Route::get('/unser', function () {
  var_dump(unserialize(Cache::get('test')));
});

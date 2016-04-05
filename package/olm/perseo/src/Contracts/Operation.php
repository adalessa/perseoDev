<?php 

namespace Olm\Perseo\Contracts;

use Olm\Perseo\TimeLength;

interface Operation
{
    public function run();
    public function process();
    public function getId();
    public function setId($id);
    public function setInput($input);
    public function input();
    public function setOutput($setOutput);
    public function output();
    public function result();
    public function setResult($result);
    public function schedule(TimeLength $length = null);
    public function getQueue();
    public function setQueue($queue);
    public function then(Operation $next);
    public function otherwise(Operation $next);
    public function thenIf($compare, Operation $next);
    public function callback(callable $callback);
    public function processInput();
    public function processOutput();
}

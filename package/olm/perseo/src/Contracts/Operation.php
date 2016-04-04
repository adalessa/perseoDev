<?php 

namespace Olm\Perseo\Contracts;

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
    public function schedule($delay = 0);
    public function getQueue();
    public function setQueue($queue);
    public function then(Operation $next);
    public function otherwise(Operation $next);
    public function thenIf($compare, Operation $next);
    public function callback(callable $callback);
}

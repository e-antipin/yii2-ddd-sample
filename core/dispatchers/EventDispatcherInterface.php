<?php

namespace core\dispatchers;

interface EventDispatcherInterface
{
    public function dispatchAll(array $events): void;
    public function dispatch($event): void;
}
<?php

namespace core\events\Shop;

class BrandSaveEvent
{
    public $entity;

    public function __construct($entity)
    {
        $this->entity = $entity;
    }
}
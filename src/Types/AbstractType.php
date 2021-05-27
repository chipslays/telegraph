<?php

namespace Chipslays\Telegraph\Types;

use Chipslays\Collection\Collection;

abstract class AbstractType
{
    /**
     * @var Collection
     */
    protected $data;

    /**
     * @return array
     */
    public function toArray()
    {
        return $this->data->toArray();
    }
}

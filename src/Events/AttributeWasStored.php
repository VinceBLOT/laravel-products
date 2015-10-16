<?php

namespace Speelpenning\Products\Events;

use Speelpenning\Contracts\Products\Attribute;

class AttributeWasStored
{
    /**
     * @var Attribute
     */
    public $attribute;

    /**
     * Create a new event.
     *
     * @param Attribute $attribute
     */
    public function __construct(Attribute $attribute)
    {
        $this->attribute = $attribute;
    }
}
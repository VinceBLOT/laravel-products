<?php

namespace Speelpenning\Products\Events;

use Speelpenning\Contracts\Products\AttributeValue;

class AttributeValueWasDestroyed
{
    /**
     * @var AttributeValue
     */
    public $attributeValue;

    /**
     * Create a new event.
     *
     * @param AttributeValue $attributeValue
     */
    public function __construct(AttributeValue $attributeValue)
    {
        $this->attributeValue = $attributeValue;
    }
}

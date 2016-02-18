<?php

namespace Speelpenning\Products\Jobs;

use Illuminate\Contracts\Events\Dispatcher;
use Speelpenning\Contracts\Products\Repositories\AttributeRepository;
use Speelpenning\Contracts\Products\Repositories\AttributeValueRepository;
use Speelpenning\Products\AttributeValue;
use Speelpenning\Products\Events\AttributeValueWasStored;

class StoreAttributeValue
{
    /**
     * @var int
     */
    protected $attributeId;

    /**
     * @var string
     */
    protected $value;

    /**
     * Create a new store attribute value job.
     *
     * @param int $attributeId
     * @param string $value
     */
    public function __construct($attributeId, $value)
    {
        $this->attributeId = $attributeId;
        $this->value = $value;
    }

    /**
     * Handles the creation of an attribute value.
     *
     * @param AttributeRepository $attributeRepository
     * @param AttributeValueRepository $attributeValueRepository
     * @param Dispatcher $event
     * @return AttributeValue
     */
    public function handle(AttributeRepository $attributeRepository, AttributeValueRepository $attributeValueRepository,
                           Dispatcher $event)
    {
        $attribute = $attributeRepository->find($this->attributeId);

        $attributeValue = AttributeValue::instantiate($attribute, $this->value);

        $attributeValueRepository->save($attributeValue);

        $event->fire(new AttributeValueWasStored($attributeValue));

        return $attributeValue;
    }
}

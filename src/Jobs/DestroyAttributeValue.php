<?php

namespace Speelpenning\Products\Jobs;

use Illuminate\Contracts\Events\Dispatcher;
use Speelpenning\Contracts\Products\Attribute;
use Speelpenning\Contracts\Products\Repositories\AttributeValueRepository;
use Speelpenning\Products\Events\AttributeValueWasDestroyed;

class DestroyAttributeValue
{
    /**
     * @var int
     */
    protected $id;

    /**
     * Create a new job.
     *
     * @param int $id
     */
    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * Handles destruction of the attribute value.
     *
     * @param AttributeValueRepository $attributeValueRepository
     * @param Dispatcher $event
     * @return Attribute
     */
    public function handle(AttributeValueRepository $attributeValueRepository, Dispatcher $event)
    {
        $attributeValue = $attributeValueRepository->find($this->id);

        $attributeValueRepository->destroy($attributeValue);

        $event->fire(new AttributeValueWasDestroyed($attributeValue));

        return $attributeValue;
    }
}
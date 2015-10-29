<?php

namespace Speelpenning\Products\Jobs;

use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Events\Dispatcher;
use Speelpenning\Contracts\Products\Repositories\AttributeValueRepository;
use Speelpenning\Products\AttributeValue;
use Speelpenning\Products\Events\AttributeValueWasUpdated;

class UpdateAttributeValue implements SelfHandling
{
    /**
     * @var string
     */
    protected $id;

    /**
     * @var string
     */
    protected $value;

    /**
     * Create a new store attribute value job.
     *
     * @param int $id
     * @param string $value
     */
    public function __construct($id, $value)
    {
        $this->id = $id;
        $this->value = $value;
    }

    /**
     * Handles the creation of an attribute value.
     *
     * @param AttributeValueRepository $attributeValueRepository
     * @param Dispatcher $event
     * @return AttributeValue
     */
    public function handle(AttributeValueRepository $attributeValueRepository, Dispatcher $event)
    {
        $attributeValue = $attributeValueRepository->find($this->id)->fill(get_object_vars($this));

        $attributeValueRepository->save($attributeValue);

        $event->fire(new AttributeValueWasUpdated($attributeValue));

        return $attributeValue;
    }
}
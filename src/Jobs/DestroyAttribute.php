<?php

namespace Speelpenning\Products\Jobs;

use Illuminate\Contracts\Events\Dispatcher;
use Speelpenning\Contracts\Products\Attribute;
use Speelpenning\Contracts\Products\Repositories\AttributeRepository;
use Speelpenning\Products\Events\AttributeWasDestroyed;

class DestroyAttribute
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
     * Handles destruction of the attribute.
     *
     * @param AttributeRepository $attributeRepository
     * @param Dispatcher $event
     * @return Attribute
     */
    public function handle(AttributeRepository $attributeRepository, Dispatcher $event)
    {
        $attribute = $attributeRepository->find($this->id);

        // TODO : Check if the attribute is in use

        $attributeRepository->destroy($attribute);

        $event->fire(new AttributeWasDestroyed($attribute));

        return $attribute;
    }
}

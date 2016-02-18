<?php

namespace Speelpenning\Products\Jobs;

use Illuminate\Contracts\Events\Dispatcher;
use Speelpenning\Contracts\Products\Repositories\AttributeRepository;
use Speelpenning\Products\Events\AttributeWasStored;
use Speelpenning\Products\Attribute;

class StoreAttribute
{
    /**
     * @var string
     */
    protected $description;

    /**
     * @var string
     */
    protected $type;

    /**
     * StoreAttribute constructor.
     *
     * @param string $description
     * @param string $type
     */
    public function __construct($description, $type)
    {
        $this->description = $description;
        $this->type = $type;
    }

    /**
     * Handles the creation of a product.
     *
     * @param AttributeRepository $attributeRepository
     * @param Dispatcher $event
     * @return Attribute
     */
    public function handle(AttributeRepository $attributeRepository, Dispatcher $event)
    {
        $attribute = Attribute::instantiate($this->description, $this->type);

        $attributeRepository->save($attribute);

        $event->fire(new AttributeWasStored($attribute));

        return $attribute;
    }
}
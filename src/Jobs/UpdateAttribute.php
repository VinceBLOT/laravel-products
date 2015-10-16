<?php

namespace Speelpenning\Products\Jobs;

use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Events\Dispatcher;
use Speelpenning\Contracts\Products\Attribute;
use Speelpenning\Contracts\Products\Repositories\AttributeRepository;
use Speelpenning\Products\Events\AttributeWasUpdated;

class UpdateAttribute implements SelfHandling
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $description;

    /**
     * UpdateAttribute constructor.
     *
     * @param int $id
     * @param string $description
     */
    public function __construct($id, $description)
    {
        $this->id = $id;
        $this->description = $description;
    }

    /**
     * Handles updating of the product type.
     *
     * @param AttributeRepository $attributeRepository
     * @param Dispatcher $event
     * @return Attribute
     */
    public function handle(AttributeRepository $attributeRepository, Dispatcher $event)
    {
        $attribute = $attributeRepository->find($this->id)->fill(get_object_vars($this));

        $attributeRepository->save($attribute);

        $event->fire(new AttributeWasUpdated($attribute));

        return $attribute;
    }
}

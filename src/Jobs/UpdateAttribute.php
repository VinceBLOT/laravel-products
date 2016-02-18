<?php

namespace Speelpenning\Products\Jobs;

use Illuminate\Contracts\Events\Dispatcher;
use Speelpenning\Contracts\Products\Attribute;
use Speelpenning\Contracts\Products\Repositories\AttributeRepository;
use Speelpenning\Products\Events\AttributeWasUpdated;

class UpdateAttribute
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
     * @var int|null
     */
    protected $maxlength;

    /**
     * @var string|null
     */
    protected $autocomplete;

    /**
     * @var string|null
     */
    protected $placeholder;

    /**
     * @var string|null
     */
    protected $pattern;

    /**
     * @var float|null
     */
    protected $min;

    /**
     * @var float|null
     */
    protected $max;

    /**
     * @var float|null
     */
    protected $step;

    /**
     * @var string|null
     */
    protected $unit_of_measurement;

    /**
     * UpdateAttribute constructor.
     *
     * @param int $id
     * @param string $description
     * @param int|null $maxlength
     * @param string|null $autocomplete
     * @param string|null $placeholder
     * @param string|null $pattern
     * @param float|null $min
     * @param float|null $max
     * @param float|null $step
     * @param string|null $unitOfMeasurement
     */
    public function __construct($id, $description,
        $maxlength = null, $autocomplete = null, $placeholder = null, $pattern = null,
        $min = null, $max = null, $step = null, $unitOfMeasurement = null)
    {
        $this->id = $id;
        $this->description = $description;

        $this->maxlength = $maxlength;
        $this->autocomplete = $autocomplete;
        $this->placeholder = $placeholder;
        $this->pattern = $pattern;

        $this->min = $min;
        $this->max = $max;
        $this->step = $step;
        $this->unit_of_measurement = $unitOfMeasurement;
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

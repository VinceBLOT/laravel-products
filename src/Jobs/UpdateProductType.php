<?php

namespace Speelpenning\Products\Jobs;

use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Events\Dispatcher;
use Speelpenning\Contracts\Products\ProductType;
use Speelpenning\Contracts\Products\Repositories\AttributeRepository;
use Speelpenning\Contracts\Products\Repositories\ProductTypeRepository;
use Speelpenning\Products\Events\ProductTypeWasUpdated;

class UpdateProductType implements SelfHandling
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
     * @var array
     */
    protected $attributes;

    /**
     * UpdateProductType constructor.
     *
     * @param int $id
     * @param string $description
     * @param array $attributes
     */
    public function __construct($id, $description, array $attributes = [])
    {
        $this->id = $id;
        $this->description = $description;
        $this->attributes = $attributes;
    }

    /**
     * Handles updating of the product type.
     *
     * @param ProductTypeRepository $productTypeRepository
     * @param AttributeRepository $attributeRepository
     * @param Dispatcher $event
     * @return ProductType
     */
    public function handle(ProductTypeRepository $productTypeRepository, AttributeRepository $attributeRepository,
                           Dispatcher $event)
    {
        $productType = $productTypeRepository->find($this->id)->fill(get_object_vars($this));

        $productTypeRepository->save($productType);

        $attributeRepository->syncWithProductType($this->attributes, $productType);

        $event->fire(new ProductTypeWasUpdated($productType));

        return $productType;
    }
}
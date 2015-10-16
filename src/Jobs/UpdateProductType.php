<?php

namespace Speelpenning\Products\Jobs;

use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Events\Dispatcher;
use Speelpenning\Contracts\Products\ProductType;
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
     * UpdateProductType constructor.
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
     * @param ProductTypeRepository $productTypeRepository
     * @param Dispatcher $event
     * @return ProductType
     */
    public function handle(ProductTypeRepository $productTypeRepository, Dispatcher $event)
    {
        $productType = $productTypeRepository->find($this->id)->fill(get_object_vars($this));

        $productTypeRepository->save($productType);

        $event->fire(new ProductTypeWasUpdated($productType));

        return $productType;
    }
}

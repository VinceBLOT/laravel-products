<?php

namespace Speelpenning\Products\Jobs;

use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Events\Dispatcher;
use Speelpenning\Products\Events\ProductTypeWasStored;
use Speelpenning\Products\ProductType;
use Speelpenning\Contracts\Products\Repositories\ProductTypeRepository;

class StoreProductType implements SelfHandling
{
    /**
     * @var string
     */
    protected $description;

    /**
     * StoreProductType constructor.
     *
     * @param string $description
     */
    public function __construct($description)
    {
        $this->description = $description;
    }

    /**
     * Handles the creation of a product.
     *
     * @param ProductTypeRepository $productTypeRepository
     * @param Dispatcher $event
     * @return ProductType
     */
    public function handle(ProductTypeRepository $productTypeRepository, Dispatcher $event)
    {
        $productType = ProductType::instantiate($this->description);

        $productTypeRepository->save($productType);

        $event->fire(new ProductTypeWasStored($productType));

        return $productType;
    }
}

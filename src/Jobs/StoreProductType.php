<?php

namespace Speelpenning\Products\Jobs;

use Illuminate\Contracts\Events\Dispatcher;
use Speelpenning\Contracts\Products\Repositories\ProductTypeRepository;
use Speelpenning\Products\Events\ProductTypeWasStored;
use Speelpenning\Products\ProductType;

class StoreProductType
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

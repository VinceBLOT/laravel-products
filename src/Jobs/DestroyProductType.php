<?php

namespace Speelpenning\Products\Jobs;

use Illuminate\Contracts\Events\Dispatcher;
use Speelpenning\Contracts\Products\ProductType;
use Speelpenning\Contracts\Products\Repositories\ProductTypeRepository;
use Speelpenning\Products\Events\ProductTypeWasDestroyed;

class DestroyProductType
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
     * Handles destruction of the product type.
     *
     * @param ProductTypeRepository $productTypeRepository
     * @param Dispatcher $event
     * @return ProductType
     */
    public function handle(ProductTypeRepository $productTypeRepository, Dispatcher $event)
    {
        $productType = $productTypeRepository->find($this->id);

        // TODO : Check if the product type is in use

        $productTypeRepository->destroy($productType);

        $event->fire(new ProductTypeWasDestroyed($productType));

        return $productType;
    }
}
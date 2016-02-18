<?php

namespace Speelpenning\Products\Jobs;

use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Events\Dispatcher;
use Speelpenning\Contracts\Products\Repositories\ProductRepository;
use Speelpenning\Contracts\Products\Repositories\ProductTypeRepository;
use Speelpenning\Products\Events\ProductWasStored;
use Speelpenning\Products\Product;
use Speelpenning\Products\ProductNumber;

class StoreProduct implements SelfHandling
{
    /**
     * @var ProductNumber
     */
    protected $productNumber;

    /**
     * @var int
     */
    protected $productTypeId;

    /**
     * @var string
     */
    protected $description;

    /**
     * @param string $productNumber
     * @param int $productTypeId
     * @param string $description
     */
    public function __construct($productNumber = null, $productTypeId, $description)
    {
        $this->productNumber = $productNumber;
        $this->productTypeId = $productTypeId;
        $this->description = $description;
    }

    /**
     * Handles the creation of a product.
     *
     * @param ProductRepository $productRepository
     * @param ProductTypeRepository $productTypeRepository
     * @param Dispatcher $event
     * @return Product
     */
    public function handle(ProductRepository $productRepository, ProductTypeRepository $productTypeRepository,
                           Dispatcher $event)
    {
        $productNumber = is_null($this->productNumber)
            ? ProductNumber::first()
            : ProductNumber::parse($this->productNumber);

        $productType = $productTypeRepository->find($this->productTypeId);
        $product = Product::instantiate($productNumber, $productType, $this->description);
        $productRepository->save($product);
        $event->fire(new ProductWasStored($product));
        return $product;
    }
}

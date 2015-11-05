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
    protected $product_number;

    /**
     * @var int
     */
    protected $product_type_id;

    /**
     * @var string
     */
    protected $description;

    /**
     * @param string $product_number
     * @param int $product_type_id
     * @param string $description
     */
    public function __construct($product_number, $product_type_id, $description)
    {
        $this->product_number = ProductNumber::parse($product_number);
        $this->product_type_id = $product_type_id;
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
        $productType = $productTypeRepository->find($this->product_type_id);
        $product = Product::instantiate($this->product_number, $productType, $this->description);
        $productRepository->save($product);
        $event->fire(new ProductWasStored($product));
        return $product;
    }
}

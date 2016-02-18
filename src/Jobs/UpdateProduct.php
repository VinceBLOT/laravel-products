<?php

namespace Speelpenning\Products\Jobs;

use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Events\Dispatcher;
use Speelpenning\Contracts\Products\Repositories\AttributeRepository;
use Speelpenning\Contracts\Products\Repositories\ProductAttributeRepository;
use Speelpenning\Contracts\Products\Repositories\ProductRepository;
use Speelpenning\Products\Events\ProductWasUpdated;
use Speelpenning\Products\ProductAttribute;

class UpdateProduct implements SelfHandling
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
     * Handles updating of the product.
     *
     * @param ProductRepository $productRepository
     * @param Dispatcher $event
     * @return Product
     */
    public function handle(ProductRepository $productRepository, Dispatcher $event)
    {
        $product = $productRepository->find($this->id)->fill(array_except(get_object_vars($this), ['attributes']));

        $productRepository->save($product, $this->attributes);

        $event->fire(new ProductWasUpdated($product));

        return $product;
    }
}

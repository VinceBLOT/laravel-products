<?php

namespace Speelpenning\Products\Jobs;

use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Events\Dispatcher;
use Speelpenning\Contracts\Products\Repositories\ProductRepository;
use Speelpenning\Products\Events\ProductWasUpdated;

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
     * @param int $id
     * @param string $description
     */
    public function __construct($id, $description)
    {
        $this->id = $id;
        $this->description = $description;
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
        $product = $productRepository->find($this->id)->fill(get_object_vars($this));

        $productRepository->save($product);

        $event->fire(new ProductWasUpdated($product));

        return $product;
    }
}

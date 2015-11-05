<?php

namespace Speelpenning\Products\Jobs;

use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Events\Dispatcher;
use Speelpenning\Contracts\Products\Repositories\ProductRepository;
use Speelpenning\Products\Events\ProductWasDestroyed;
use Speelpenning\Products\Product;

class DestroyProduct implements SelfHandling
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
     * Handles destruction of the attribute.
     *
     * @param ProductRepository $productRepository
     * @param Dispatcher $event
     * @return Product
     */
    public function handle(ProductRepository $productRepository, Dispatcher $event)
    {
        $product = $productRepository->find($this->id);

        $productRepository->destroy($product);

        $event->fire(new ProductWasDestroyed($product));

        return $product;
    }
}

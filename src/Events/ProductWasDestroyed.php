<?php

namespace Speelpenning\Products\Events;

use Speelpenning\Contracts\Products\Product;

class ProductWasDestroyed
{
    /**
     * @var Product
     */
    public $product;

    /**
     * Create a new event.
     *
     * @param Product $product
     */
    public function __construct(Product $product)
    {
        $this->product = $product;
    }
}

<?php

namespace Speelpenning\Products\Events;

use Speelpenning\Contracts\Products\ProductType;

class ProductTypeWasDestroyed
{
    /**
     * @var ProductType
     */
    public $productType;

    /**
     * Create a new event.
     *
     * @param ProductType $productType
     */
    public function __construct(ProductType $productType)
    {
        $this->productType = $productType;
    }
}

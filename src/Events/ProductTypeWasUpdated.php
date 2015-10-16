<?php

namespace Speelpenning\Products\Events;

use Speelpenning\Contracts\Products\ProductType;

class ProductTypeWasUpdated
{
    /**
     * @var ProductType
     */
    public $productType;

    /**
     * ProductTypeWasUpdated constructor.
     * @param ProductType $productType
     */
    public function __construct(ProductType $productType)
    {
        $this->productType = $productType;
    }
}

<?php

namespace Speelpenning\Products\Events;

use Speelpenning\Contracts\Products\ProductType;

class ProductTypeWasStored
{
    /**
     * @var ProductType
     */
    public $productType;

    /**
     * ProductTypeWasCreated constructor.
     * @param ProductType $productType
     */
    public function __construct(ProductType $productType)
    {
        $this->productType = $productType;
    }
}

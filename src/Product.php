<?php

namespace Speelpenning\Products;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Speelpenning\Contracts\Products\Product as ProductContract;
use Speelpenning\Contracts\Products\ProductNumber as ProductNumberContract;
use Speelpenning\Contracts\Products\ProductType as ProductTypeContract;

class Product extends Model implements ProductContract
{
    use SoftDeletes;

    protected $table = 'products';

    protected $fillable = ['product_number', 'product_type_id', 'description'];

    /**
     * Instantiate a new product.
     *
     * @param ProductNumberContract $productNumber
     * @param ProductTypeContract $productType
     * @param string $description
     * @return ProductContract
     */
    public static function instantiate(ProductNumberContract $productNumber, ProductTypeContract $productType, $description)
    {
        return new static([
            'product_number' => $productNumber,
            'product_type_id' => $productType->id,
            'description' => $description,
        ]);
    }

    /**
     * Returns the product number domain object.
     *
     * @param string $value
     * @return ProductNumber
     */
    public function getProductNumberAttribute($value)
    {
        return new ProductNumber($value);
    }
}

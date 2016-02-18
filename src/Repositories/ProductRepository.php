<?php

namespace Speelpenning\Products\Repositories;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Speelpenning\Contracts\Products\Repositories\ProductRepository as ProductRepositoryContract;
use Speelpenning\Contracts\Products\Product as ProductContract;
use Speelpenning\Products\Product;
use Speelpenning\Products\ProductAttribute;
use Speelpenning\Products\ProductNumber;

class ProductRepository implements ProductRepositoryContract
{
    /**
     * @var Repository
     */
    protected $config;

    /**
     * Create a new product repository.
     *
     * @param Repository $config
     */
    public function __construct(Repository $config)
    {
        $this->config = $config;
    }

    /**
     * Removes a product from the repository.
     *
     * @param ProductContract $product
     * @return bool
     */
    public function destroy(ProductContract $product)
    {
        return $product->delete();
    }

    /**
     * Finds a product by id.
     *
     * @param int $id
     * @param array $with
     * @return ProductContract
     */
    public function find($id, array $with = [])
    {
        return Product::with($with)->findOrFail($id);
    }

    /**
     * Returns the highest product number.
     *
     * @return string
     */
    public function getHighestProductNumber()
    {
        $productNumber = Product::max('product_number');

        if ( ! $productNumber) {
            $productNumber = ProductNumber::first();
        }

        return $productNumber;
    }

    /**
     * Queries the product catalogue and returns a paginated result.
     *
     * @param null|string $q
     * @return LengthAwarePaginator
     */
    public function query($q = null)
    {
        return Product::where(function ($query) use ($q) {
                if ($q) {
                    foreach (explode(' ', $q) as $keyword) {
                        $query->where('description', 'like', "%{$keyword}%");
                    }
                }
            })
            ->orderBy('description')
            ->paginate();
    }

    /**
     * Stores a product.
     *
     * @param ProductContract $product
     * @param array $attributes
     * @return bool
     */
    public function save(ProductContract $product, array $attributes = [])
    {
        $result = $product->save();

        $details = [];
        foreach ($attributes as $key => $value) {
            if ($value) {
                $details[$key] = compact('value');
            }
        }
        $product->attributes()->sync($details);

        return $result;
    }
}

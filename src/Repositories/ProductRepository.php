<?php

namespace Speelpenning\Products\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Speelpenning\Contracts\Products\Repositories\ProductRepository as ProductRepositoryContract;
use Speelpenning\Contracts\Products\Product as ProductContract;
use Speelpenning\Products\Product;

class ProductRepository implements ProductRepositoryContract
{
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
     * @return ProductContract
     */
    public function find($id)
    {
        return Product::findOrFail($id);
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
                        $query->where('description', 'like', "{$keyword}%");
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
     * @return bool
     */
    public function save(ProductContract $product)
    {
        return $product->save();
    }
}

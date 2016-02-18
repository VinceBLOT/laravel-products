<?php

namespace Speelpenning\Products\Repositories;

use Illuminate\Support\Collection;
use Speelpenning\Contracts\Products\Product;
use Speelpenning\Contracts\Products\ProductType as ProductTypeContract;
use Speelpenning\Contracts\Products\Repositories\ProductTypeRepository as ProductTypeRepositoryContract;
use Speelpenning\Products\ProductType;

class ProductTypeRepository implements ProductTypeRepositoryContract
{
    /**
     * Returns a collection with all product types.
     *
     * @return Collection
     */
    public function all()
    {
        return ProductType::orderBy('description')->get();
    }

    /**
     * Destroys a product type.
     *
     * @param ProductTypeContract $productType
     * @return bool
     */
    public function destroy(ProductTypeContract $productType)
    {
        return (bool)$productType->delete();
    }

    /**
     * Finds a product type by id.
     *
     * @param int $id
     * @param array $relations
     * @return ProductType
     * @throws ModelNotFoundException
     */
    public function find($id, array $relations = [])
    {
        return ProductType::with($relations)->findOrFail($id);
    }

    /**
     * Returns the product type belonging to the given product.
     *
     * @param Product $product
     * @return ProductTypeContract
     */
    public function getByProduct(Product $product)
    {
        return $product->productType;
    }


    /**
     * Returns a collection of product types.
     *
     * @param string|null $q
     * @return LengthAwarePaginator
     */
    public function query($q = null)
    {
        return ProductType::where(function ($query) use ($q) {
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
     * Stores a product type.
     *
     * @param ProductTypeContract $productType
     * @return bool
     */
    public function save(ProductTypeContract $productType)
    {
        return $productType->save();
    }
}
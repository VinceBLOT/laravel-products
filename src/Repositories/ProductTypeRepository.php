<?php

namespace Speelpenning\Products\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Speelpenning\Contracts\Products\ProductType as ProductTypeContract;
use Speelpenning\Contracts\Products\Repositories\ProductTypeRepository as ProductTypeRepositoryContract;
use Speelpenning\Products\ProductType;

class ProductTypeRepository implements ProductTypeRepositoryContract
{
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
     * @return ProductType
     * @throws ModelNotFoundException
     */
    public function find($id)
    {
        return ProductType::findOrFail($id);
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
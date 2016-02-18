<?php

namespace Speelpenning\Products\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Speelpenning\Contracts\Products\Attribute as AttributeContract;
use Speelpenning\Contracts\Products\ProductType as ProductTypeContract;
use Speelpenning\Contracts\Products\Repositories\AttributeRepository as AttributeRepositoryContract;
use Speelpenning\Products\Attribute;

class AttributeRepository implements AttributeRepositoryContract
{
    /**
     * Removes the attribute from the repository.
     *
     * @param AttributeContract $attribute
     * @return bool
     */
    public function destroy(AttributeContract $attribute)
    {
        return $attribute->delete();
    }

    /**
     * Finds an attribute by id.
     *
     * @param string $id
     * @return AttributeContract
     */
    public function find($id)
    {
        return Attribute::findOrFail($id);
    }

    /**
     * Relates attributes to a product type.
     *
     * @param int|array $attributeIds
     * @param ProductTypeContract $productType
     * @return array
     */
    public function relateToProductType($attributeIds, ProductTypeContract $productType)
    {
        return $productType->attributes()->sync(is_array($attributeIds) ? $attributeIds : [$attributeIds]);
    }

    /**
     * Returns a collection of attributes that belongs to the given product type.
     *
     * @param ProductTypeContract $productType
     * @return Collection
     */
    public function getByProductType(ProductTypeContract $productType)
    {
        return $productType->attributes()->orderBy('description')->get();
    }

    /**
     * Returns a collection of all attributes.
     *
     * @return Collection
     */
    public function all()
    {
        return Attribute::orderBy('description')->get();
    }

    /**
     * Returns a collection of attributes matching the query.
     *
     * @param string|null $q
     * @return LengthAwarePaginator
     */
    public function query($q = null)
    {
        return Attribute::where(function ($query) use ($q) {
            foreach (explode(' ', $q) as $keyword) {
                $query->where('description', 'like', "%{$keyword}%");
            }
        })->orderBy('description')->paginate();
    }

    /**
     * Stores an attribute.
     *
     * @param AttributeContract $attribute
     * @return bool
     */
    public function save(AttributeContract $attribute)
    {
        return $attribute->save();
    }

    /**
     * Relates attributes to a product type.
     *
     * @param ProductTypeContract $productType
     * @param int|array $attributeIds
     * @param array $requiredAttributes
     * @return array
     */
    public function syncWithProductType(ProductTypeContract $productType, $attributeIds, array $requiredAttributes = [])
    {
        foreach ($attributeIds as $id) {
            array_set($attributeIds, $id, ['required' => in_array($id, $requiredAttributes)]);
        }
        $productType->sync($attributeIds);
    }


}
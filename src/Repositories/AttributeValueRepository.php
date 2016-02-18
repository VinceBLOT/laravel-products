<?php

namespace Speelpenning\Products\Repositories;

use Illuminate\Support\Collection;
use Speelpenning\Contracts\Products\Attribute as AttributeContract;
use Speelpenning\Contracts\Products\AttributeValue as AttributeValueContract;
use Speelpenning\Contracts\Products\Repositories\AttributeValueRepository as AttributeValueRepositoryContract;
use Speelpenning\Products\AttributeValue;

class AttributeValueRepository implements AttributeValueRepositoryContract
{
    /**
     * Removes an attribute value from the repository.
     *
     * @param AttributeValueContract $attributeValue
     * @return bool
     */
    public function destroy(AttributeValueContract $attributeValue)
    {
        return $attributeValue->delete();
    }

    /**
     * Finds an attribute value by id.
     *
     * @param int $id
     * @return AttributeValue
     */
    public function find($id)
    {
        return AttributeValue::findOrFail($id);
    }

    /**
     * Returns a collection of attribute values belonging to a specific attribute.
     *
     * @param AttributeContract $attribute
     * @return Collection
     */
    public function getByAttribute(AttributeContract $attribute)
    {
        return $attribute->attributeValues()->orderBy('value')->get();
    }

    /**
     * Saves an attribute value.
     *
     * @param AttributeValueContract $attributeValue
     * @return bool
     */
    public function save(AttributeValueContract $attributeValue)
    {
        return $attributeValue->save();
    }
}

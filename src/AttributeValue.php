<?php

namespace Speelpenning\Products;

use Illuminate\Database\Eloquent\Model;
use Speelpenning\Contracts\Products\Attribute as AttributeContract;
use Speelpenning\Contracts\Products\AttributeValue as AttributeValueContract;
use Speelpenning\Products\Exceptions\AttributeMustSupportValues;

class AttributeValue extends Model implements AttributeValueContract
{
    protected $table = 'attribute_values';

    protected $fillable = ['attribute_id', 'value'];

    /**
     * Instantiates a new attribute value.
     *
     * @param AttributeContract $attribute
     * @param string $value
     * @return AttributeValueContract
     */
    public static function instantiate(AttributeContract $attribute, $value)
    {
        if ( ! $attribute->supportsValues()) {
            throw new AttributeMustSupportValues();
        }

        return new static([
            'attribute_id' => $attribute->id,
            'value' => $value,
        ]);
    }
}
<?php

namespace Speelpenning\Products;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Speelpenning\Contracts\Products\Attribute as AttributeContract;

class Attribute extends Model implements AttributeContract
{
    use SoftDeletes;

    /**
     * The database table.
     *
     * @var string
     */
    protected $table = 'attributes';

    /**
     * Fields that allow mass assignment.
     *
     * @var array
     */
    protected $fillable = ['description', 'type', 'unit_of_measurement'];

    /**
     * Instantiates a new attribute.
     *
     * @param string $description
     * @param string $type
     * @return static
     */
    public static function instantiate($description, $type)
    {
        return new static(compact('description', 'type'));
    }

    /**
     * Returns an array with allowed attribute types following the Laravel validation rules.
     *
     * @return array
     */
    public static function getAllowedTypes()
    {
        return ['string', 'numeric', 'in'];
    }

    /**
     * Indicates if the attribute supports values.
     *
     * @return bool
     */
    public function supportsValues()
    {
        return $this->type == 'in';
    }

    /**
     * Returns the attribute value relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function attributeValues()
    {
        return $this->hasMany(AttributeValue::class);
    }
}
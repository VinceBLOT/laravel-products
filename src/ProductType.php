<?php

namespace Speelpenning\Products;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Speelpenning\Contracts\Products\ProductType as ProductTypeContract;

class ProductType extends Model implements ProductTypeContract
{
    use SoftDeletes;

    /**
     * The database table name.
     *
     * @var string
     */
    protected $table = 'product_types';

    /**
     * Attributes that allow mass assignment.
     *
     * @var array
     */
    protected $fillable = ['description'];

    /**
     * Instantiate a new product type.
     *
     * @param string $description
     * @return ProductTypeContract
     */
    public static function instantiate($description)
    {
        return new static(compact('description'));
    }

    /**
     * Returns the attribute relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function attributes()
    {
        return $this->belongsToMany(Attribute::class);
    }
}

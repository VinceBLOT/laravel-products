<?php

namespace Speelpenning\Products;

use Illuminate\Contracts\Validation\ValidationException;
use InvalidArgumentException;
use Speelpenning\Contracts\Products\ProductNumber as ProductNumberContract;

class ProductNumber implements ProductNumberContract
{
    /**
     * @var string
     */
    private $productNumber;

    /**
     * Create a new product number.
     *
     * @param string $productNumber
     */
    public function __construct($productNumber)
    {
        $this->validate($productNumber);
        $this->productNumber = $productNumber;
    }

    /**
     * Returns the string representation of the product number.
     *
     * @return string
     */
    public function __toString()
    {
        return (string)$this->productNumber;
    }

    /**
     * Indicates if the product number should increment automatically.
     *
     * @return bool
     */
    public function autoIncrements()
    {
        return config('products.productNumber.autoIncrements');
    }

    /**
     * Returns the length that a product number must have.
     *
     * @return int
     */
    public function length()
    {
        return config('products.productNumber.length');
    }

    /**
     * Returns an instance with the next unused product number.
     *
     * @return ProductNumberContract|null
     */
    public function next()
    {
        return $this->autoIncrements() ? static::parse((int)$this->productNumber + 1) : null;
    }

    /**
     * Parses a product number and returns a product number instance.
     *
     * @param string $productNumber
     * @return ProductNumberContract
     * @throws ValidationException
     */
    public static function parse($productNumber)
    {
        return new static($productNumber);
    }

    /**
     * Checks if the product number format is valid.
     *
     * @param string $value
     * @throws InvalidArgumentException
     */
    protected function validate($value)
    {
        $pattern = '/^[1-9]{1}[0-9]{' . ($this->length() - 1) . '}$/';

        if (! preg_match($pattern, $value)) {
            throw new InvalidArgumentException("Product number [{$value}] does not match pattern {$pattern}");
        }
    }
}

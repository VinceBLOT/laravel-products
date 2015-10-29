<?php

use Speelpenning\Contracts\Products\Repositories\AttributeRepository;
use Speelpenning\Contracts\Products\Repositories\AttributeValueRepository;
use Speelpenning\Products\Attribute;
use Speelpenning\Products\AttributeValue;

class AttributeValueRepositoryTest extends TestCase
{
    /**
     * @var AttributeRepository
     */
    protected $attributeRepository;

    /**
     * @var AttributeValueRepository
     */
    protected $attributeValueRepository;

    public function setUp()
    {
        parent::setUp();

        $this->artisan('vendor:publish', ['force']);
        $this->artisan('migrate');

        $this->attributeRepository = app(AttributeRepository::class);
        $this->attributeValueRepository = app(AttributeValueRepository::class);
    }

    protected function createAttribute()
    {
        $attribute = Attribute::instantiate('Gender', 'in');
        $this->attributeRepository->save($attribute);
        return $attribute;
    }

    public function testItFindsAttributeValuesById()
    {
        $attribute = $this->createAttribute();

        $attributeValue = AttributeValue::instantiate($attribute, 'Not specified');
        $this->attributeValueRepository->save($attributeValue);

        $this->assertInstanceOf(
            \Speelpenning\Contracts\Products\AttributeValue::class,
            $this->attributeValueRepository->find(1));
    }

    public function testItFindsAttributeValuesByAttribute()
    {
        $attribute = $this->createAttribute();

        foreach(['Male', 'Female', 'Not specified'] as $value) {
            $attributeValue = AttributeValue::instantiate($attribute, $value);
            $this->attributeValueRepository->save($attributeValue);
        }

        $this->assertCount(3, $this->attributeValueRepository->getByAttribute($attribute));
    }

    public function testItRemovesAttributeValues()
    {
        $attribute = $this->createAttribute();

        $attributeValue = AttributeValue::instantiate($attribute, 'Not specified');
        $this->attributeValueRepository->save($attributeValue);

        $this->assertTrue($this->attributeValueRepository->destroy($attributeValue));
        $this->assertEmpty($this->attributeValueRepository->getByAttribute($attribute));
    }
}
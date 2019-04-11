<?php declare(strict_types=1);

namespace Raw\CustomerPrice\Custom;

use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;

/**
 * @method void              add(CustomEntity $entity)
 * @method void              set(string $key, CustomEntity $entity)
 * @method CustomPriceEntity[]    getIterator()
 * @method CustomPriceEntity[]    getElements()
 * @method CustomPriceEntity|null get(string $key)
 * @method CustomPriceEntity|null first()
 * @method CustomPriceEntity|null last()
 */
class CustomPriceEntityCollection extends EntityCollection
{
    protected function getExpectedClass(): string
    {
        return CustomPriceEntity::class;
    }
}
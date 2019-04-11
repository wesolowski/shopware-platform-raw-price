<?php declare(strict_types=1);

namespace Raw\CustomerPrice\Custom;

use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\CreatedAtField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\StringField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\UpdatedAtField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;

class CustomPriceEntityDefinition extends EntityDefinition
{
    public static function getEntityName(): string
    {
        return 'customer_price';
    }

    protected static function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new StringField('key', 'key'))->addFlags(new Required()),
            new JsonField('value', 'value'),
            new CreatedAtField(),
            new UpdatedAtField(),
        ]);
    }

    public static function getCollectionClass(): string
    {
        return RawCustomPriceEntityCollection::class;
    }

    public static function getEntityClass(): string
    {
        return CustomPriceEntity::class;
    }
}
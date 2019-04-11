<?php declare(strict_types=1);

namespace Raw\CustomerPrice\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Migration\MigrationStep;

class Migration1552484872Custom extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1552484872;
    }

    public function update(Connection $connection): void
    {
        $sql = <<<SQL
    CREATE TABLE `customer_price` (
        `id` BINARY(16) NOT NULL,
        `key` VARCHAR(60) NOT NULL COLLATE 'utf8mb4_unicode_ci',
        `value` JSON NOT NULL,
        `created_at` DATETIME(3) NOT NULL,
        `updated_at` DATETIME(3),
        UNIQUE INDEX `key` (`key`)
    )
    COLLATE='utf8mb4_unicode_ci'
    ENGINE=InnoDB
    ;
SQL;
        $connection->executeQuery($sql);
    }

    public function updateDestructive(Connection $connection): void
    {
    }
}
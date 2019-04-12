<?php

namespace Raw\CustomerPrice\Import\Business\Update;

use Shopware\Core\Framework\DataAbstractionLayer\Search\EntitySearchResult;
use Symfony\Component\DependencyInjection\ContainerInterface;

class PriceImportUpdate implements PriceImportUpdateInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * PriceImportUpdate constructor.
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param array $importData
     * @param EntitySearchResult $entities
     * @param $context
     */
    public function updatePriceImport(array $importData, EntitySearchResult $entities, $context): void
    {
        $this->process($importData, $entities, $context);
    }

    /**
     * @param array $importData
     * @param EntitySearchResult $entities
     * @param $context
     */
    private function process(array $importData, EntitySearchResult $entities, $context): void
    {
        $customerPriceRepo = $this->container->get('customer_price.repository');

        $elements = $entities->getElements();

        /** @var CustomPriceEntity $customer */
        $customer = array_shift($elements);

        if ($importData == $customer->getValue()) {
            $customer->setValue($importData);

            $customerPriceRepo->update(
                [
                    $customer->toArray()
                ],
                $context
            );
        }

    }
}
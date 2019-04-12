<?php

namespace Raw\CustomerPrice\Import\Business\Check;

use Raw\CustomerPrice\Import\Business\Create\PriceImportCreateInterface;
use Raw\CustomerPrice\Import\Business\Update\PriceImportUpdateInterface;
use Raw\CustomerPrice\Import\Communication\Messenger\Message\QueuePriceImportMessage;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;

class PriceImportCheck implements PriceImportCheckInterface
{
    /**
     * @var PriceImportCreateInterface
     */
    private $priceImportCreate;

    /**
     * @var PriceImportUpdateInterface
     */
    private $priceImportUpdate;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * PriceImportCheck constructor.
     *
     * @param PriceImportCreateInterface $priceImportCreate
     * @param PriceImportUpdateInterface $priceImportUpdate
     * @param ContainerInterface $container
     */
    public function __construct(
        PriceImportCreateInterface $priceImportCreate,
        PriceImportUpdateInterface $priceImportUpdate,
        ContainerInterface $container
    ) {
        $this->priceImportCreate = $priceImportCreate;
        $this->priceImportUpdate = $priceImportUpdate;
        $this->container = $container;
    }

    /**
     * @param QueuePriceImportMessage $importMessage
     * @throws \Shopware\Core\Framework\DataAbstractionLayer\Exception\InconsistentCriteriaIdsException
     */
    public function checkPriceImport(QueuePriceImportMessage $importMessage)
    {
        $importData = $importMessage->getImportData();

        $context = \Shopware\Core\Framework\Context::createDefaultContext();
        $customerPriceRepo = $this->container->get('customer_price.repository');

        /** @var EntitySearchResult $entities */
        $entities = $customerPriceRepo->search(
            (new Criteria())->addFilter(new EqualsFilter('key', '10001:12454')),
            $context
        );

        if ($entities->getTotal() !== 1) {
            $this->priceImportCreate->createPriceImport($importData);
        } else {
            $this->priceImportUpdate->updatePriceImport($importData, $entities, $context);
        }
    }
}
<?php

namespace Raw\CustomerPrice\Import\Business\Check;

use Raw\CustomerPrice\Import\Business\Create\PriceImportCreateInterface;
use Raw\CustomerPrice\Import\Business\Update\PriceImportUpdateInterface;
use Raw\CustomerPrice\Import\Communication\Messenger\Message\QueuePriceImportMessage;

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
     * PriceImportCheck constructor.
     *
     * @param PriceImportCreateInterface $priceImportCreate
     * @param PriceImportUpdateInterface $priceImportUpdate
     */
    public function __construct(
        PriceImportCreateInterface $priceImportCreate,
        PriceImportUpdateInterface $priceImportUpdate
    ) {
        $this->priceImportCreate = $priceImportCreate;
        $this->priceImportUpdate = $priceImportUpdate;
    }


    /**
     * @param QueuePriceImportMessage $importMessage
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


            $customerPriceRepo->create(
                [
                    ['key' => '10001:12454', 'value' => $importData],
                ],
                \Shopware\Core\Framework\Context::createDefaultContext()
            );

        } else {
            $elements = $entities->getElements();
            /** @var CustomPriceEntity $customer */
            $customer = array_shift($elements);

            if ($importData !== $customer->getValue()) {
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
}
<?php

namespace Raw\CustomerPrice\Import\Communication\Messenger\Handler;

use Raw\CustomerPrice\Custom\CustomPriceEntity;
use Raw\CustomerPrice\Import\Communication\Messenger\Message\QueuePriceImportMessage;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\EntitySearchResult;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class QueuePriceImportHandler implements MessageHandlerInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * QueuePriceImportHandler constructor.
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function __invoke(QueuePriceImportMessage $importMessage)
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
}
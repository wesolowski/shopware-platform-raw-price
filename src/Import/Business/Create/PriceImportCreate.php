<?php

namespace Raw\CustomerPrice\Import\Business\Create;

use Symfony\Component\DependencyInjection\ContainerInterface;

class PriceImportCreate implements PriceImportCreateInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * PriceImportCreate constructor.
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param array $importData
     */
    public function createPriceImport(array $importData): void
    {
        $this->process($importData);
    }

    /**
     * @param array $importData
     */
    private function process(array $importData): void
    {
        $customerPriceRepo = $this->container->get('customer_price.repository');

        $customerPriceRepo->create(
            [
                ['key' => '10001:12454', 'value' => $importData],
            ],
            \Shopware\Core\Framework\Context::createDefaultContext()
        );
    }
}
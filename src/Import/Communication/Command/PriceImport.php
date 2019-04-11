<?php declare(strict_types=1);


namespace Raw\CustomerPrice\Import\Communication\Command;

use Raw\CustomerPrice\Custom\CustomPriceEntity;
use Shopware\Core\Framework\DataAbstractionLayer\Search\EntitySearchResult;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\Uuid\Uuid;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;

class PriceImport extends Command
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @param $container
     */
    public function __construct($container)
    {
        $this->container = $container;
        parent::__construct();
    }


    protected function configure(): void
    {
        $this->setName('raw:customer-price:import');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $value = [['qu' => 1, 'price' => 12.20,]];
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
                    ['key' => '10001:12454', 'value' => $value],
                ],
                \Shopware\Core\Framework\Context::createDefaultContext()
            );
        } else {
            $elements = $entities->getElements();
            /** @var CustomPriceEntity $customer */
            $customer = array_shift($elements);
            dump($customer->getValue());
            dump(array_shift($elements));
            if ($value !== $customer->getValue()) {
                $customer->setValue($value);
                $customerPriceRepo->update(
                    [
                        $customer->toArray()
                    ],
                    $context
                );
            }
        }

        $output->writeln('Do something...');
    }
}
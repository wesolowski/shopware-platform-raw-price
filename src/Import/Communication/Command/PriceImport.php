<?php declare(strict_types=1);


namespace Raw\CustomerPrice\Import\Communication\Command;

use Raw\CustomerPrice\Custom\CustomPriceEntity;
use Raw\CustomerPrice\Import\Communication\Messenger\Message\QueuePriceImportMessage;
use Shopware\Core\Framework\DataAbstractionLayer\Search\EntitySearchResult;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\Uuid\Uuid;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Symfony\Component\Messenger\MessageBusInterface;

class PriceImport extends Command
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var MessageBusInterface
     */
    private $messageBus;

    /**
     * @param $container
     * @param MessageBusInterface $messageBus
     */
    public function __construct($container, MessageBusInterface $messageBus)
    {
        $this->container = $container;
        $this->messageBus = $messageBus;
        parent::__construct();
    }


    protected function configure(): void
    {
        $this->setName('raw:customer-price:import');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $importData = [['qu' => 1, 'price' => 12.20]];

        $this->messageBus->dispatch(new QueuePriceImportMessage($importData));

        return;
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
<?php

namespace Raw\CustomerPrice\Import\Communication\Messenger\Handler;

use Raw\CustomerPrice\Custom\CustomPriceEntity;
use Raw\CustomerPrice\Import\Business\CustomerPriceImportFacadeInterface;
use Raw\CustomerPrice\Import\Communication\Messenger\Message\QueuePriceImportMessage;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class QueuePriceImportHandler implements MessageHandlerInterface
{
    /**
     * @var CustomerPriceImportFacadeInterface
     */
    private $customerPriceImportFacade;

    /**
     * QueuePriceImportHandler constructor.
     *
     * @param CustomerPriceImportFacadeInterface $customerPriceImportFacade
     */
    public function __construct(CustomerPriceImportFacadeInterface $customerPriceImportFacade)
    {
        $this->customerPriceImportFacade = $customerPriceImportFacade;
    }

    public function __invoke(QueuePriceImportMessage $importMessage)
    {
        $this->customerPriceImportFacade->checkPriceImport($importMessage);
    }
}
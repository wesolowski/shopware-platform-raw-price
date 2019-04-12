<?php declare(strict_types=1);


namespace Raw\CustomerPrice\Import\Communication\Event;

use Raw\CustomerPrice\Custom\CustomPriceEntity;
use Shopware\Core\Checkout\Customer\CustomerEntity;
use Shopware\Core\Framework\DataAbstractionLayer\Search\EntitySearchResult;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ProductPageLoadedEvent implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            \Shopware\Storefront\Page\Product\ProductPageLoadedEvent::NAME => 'checkPrice'
        ];
    }

    public function checkPrice(\Shopware\Storefront\Page\Product\ProductPageLoadedEvent $productPageLoadedEvent)
    {
        $productPage = $productPageLoadedEvent->getPage()->getProduct();
        /** @var SalesChannelContext $context */
        $context = $productPageLoadedEvent->getContext();
        if ($context->getCustomer() instanceof CustomerEntity) {
            $customerNumber = $context->getCustomer()->getCustomerNumber();
            $productNumber = $productPage->getProductNumber();
        }
        dump(func_get_args(), $productPageLoadedEvent);
    }

}
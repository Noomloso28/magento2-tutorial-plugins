<?php declare(strict_types=1);

namespace Acme\PoNumber\Plugin;

use Acme\PoNumber\Model\ResourceModel\SalesOrder\Collection;
use Acme\PoNumber\Model\ResourceModel\SalesOrder\CollectionFactory;
use Magento\Sales\Api\OrderRepositoryInterface;

class AddPoNumberToSalesOrder
{
    private CollectionFactory $acmeSalesOrderCollectionFactory;

    /**
     * AddPoNumberToSalesOrder constructor.
     * @param CollectionFactory $acmeSalesOrderCollectionFactory
     */
    public function __construct(
        CollectionFactory $acmeSalesOrderCollectionFactory
    ) {
        $this->acmeSalesOrderCollectionFactory = $acmeSalesOrderCollectionFactory;
    }

    /**
     * @param OrderRepositoryInterface $subject
     * @param $result
     * @return mixed
     */
    public function afterGet(
        OrderRepositoryInterface $subject,
        $result
    ) {
        /** @var Collection $acmeSalesOrderCollection */
        $acmeSalesOrderCollection = $this->acmeSalesOrderCollectionFactory->create();
        $acmeSalesOrder = $acmeSalesOrderCollection
            ->addFieldToFilter('order_id', $result->getId())
            ->getFirstItem();

        $extensionAttributes = $result->getExtensionAttributes();
        $extensionAttributes->setData('po_number', $acmeSalesOrder->getData('po_number'));
        $result->setExtensionAttributes($extensionAttributes);

        return $result;
    }

    /**
     * @param OrderRepositoryInterface $subject
     * @param $result
     * @return mixed
     */
    public function afterGetList(
        OrderRepositoryInterface $subject,
        $result
    ) {
        foreach ($result->getItems() as $order) {
            $this->afterGet($subject, $order);
        }

        return $result;
    }
}

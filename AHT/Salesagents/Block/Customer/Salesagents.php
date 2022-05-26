<?php

namespace AHT\Salesagents\Block\Customer;

class Salesagents extends \Magento\Framework\View\Element\Template
{
    protected $_productCollectionFactory;
    protected $_customerSession;
    protected $_resource;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\App\ResourceConnection $Resource,


        array $data = []
    ) {
        $this->_customerSession = $customerSession;
        $this->_resource = $Resource;
        $this->_productCollectionFactory = $productCollectionFactory;
        parent::__construct($context, $data);
    }

    public function getProductCollection()
    {   
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

        $customerSession = $objectManager->create("Magento\Customer\Model\Session");

        if ($customerSession->isLoggedIn()) {
            $customerId = $customerSession->getCustomerId();
        }
        $page = ($this->getRequest()->getParam('p')) ? $this->getRequest()->getParam('p') : 1;
        $pageSize = ($this->getRequest()->getParam('limit')) ? $this->getRequest()->getParam('limit') : 5;

        $collection = $this->_productCollectionFactory->create()->addAttributeToSelect(
            '*'
        )->addFieldToFilter(
            'sale_agent_id',
            $customerId
        )->addAttributeToSort(
            'name'
        );
        $collection->setPageSize($pageSize)->setCurPage($page);
        return $collection;
        
    }
}

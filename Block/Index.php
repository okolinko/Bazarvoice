<?php

namespace Hunters\Bazaarvoice\Block;

use Hunters\Bazaarvoice\Service\AddProductDatabase;

class Index extends \Magento\Framework\View\Element\Template
{
	/**
	 * @var AddProductDatabase
	 */
	private $AddProductDatabase;
	protected $_pageFactory;
	protected $_postFactory;
	protected $_registry;

	/**
	 * @param AddProductDatabase                   $AddProductDatabase
	 */
	public function __construct(
		AddProductDatabase  $AddProductDatabase,
		\Magento\Framework\View\Element\Template\Context $context,
		\Magento\Framework\View\Result\PageFactory $pageFactory,
		\Hunters\Bazaarvoice\Model\PostFactory $postFactory,
		\Magento\Framework\Registry $registry,
		array $data = []
	) {
		$this->AddProductDatabase = $AddProductDatabase;
		$this->_pageFactory = $pageFactory;
		$this->_postFactory = $postFactory;
		$this->_registry = $registry;
		parent::__construct($context, $data);
	}

	public function _prepareLayout()
    {
        parent::_prepareLayout();
		$this->pageConfig->getTitle()->set(__('Custom Pagination'));
		if ($this->getRewardHistory()) {
			$pager = $this->getLayout()->createBlock(
				'Magento\Theme\Block\Html\Pager',
				'Hunters\Bazaarvoice\Block\Index'
			)->setAvailableLimit(array(5=>5,10=>10,15=>15,20=>20))
				->setShowPerPage(true)->setCollection(
					$this->getRewardHistory()
				);
			$this->setChild('pager', $pager);
			$this->getRewardHistory()->load();
		}
		return $this;
    }

    public function getPagerHtml()
	{
		return $this->getChildHtml('pager');
	}

	Public function getRewardHistory()
	{
		//get values of current page
		$page=($this->getRequest()->getParam('p'))? $this->getRequest()->getParam('p') : 1;
		//get values of current limit
		$pageSize=($this->getRequest()->getParam('limit'))? $this->getRequest
		()->getParam('limit') : 5;
		$post = $this->_postFactory->create();
		$currentProduct = $this->getCurrentProduct();
		$sku = $currentProduct->getSku();
		$collection = $post->getCollection()->addFieldToFilter(
			'sku',
			$sku);
		$collection->setPageSize($pageSize);
		$collection->setCurPage($page);
		return $collection;
	}

    public function getCurrentCategory()
    {       
        return $this->_registry->registry('current_category');
    }

    public function getCurrentProduct()
    {       
        return $this->_registry->registry('current_product');
    }  

    public function getMyCustomMethod()
	{
		// $this->AddProductDatabase->apibazaarvoice();
		$post = $this->_postFactory->create();
//		$collection = $post->getCollection()->getData();
		$collection = $post->getCollection();
		return $collection;
	}
}
<?php


namespace Hunters\Bazaarvoice\Block;
use Hunters\Bazaarvoice\Service\AddProductDatabase;


class Display extends \Magento\Framework\View\Element\Template
{

	protected $_postFactory;
	protected $_registry;


	public function __construct(
		\Hunters\Bazaarvoice\Model\PostFactory $postFactory,
		\Magento\Framework\Registry $registry,
		\Magento\Framework\View\Element\Template\Context $context)
	{
		$this->_postFactory = $postFactory;
		$this->_registry = $registry;
		parent::__construct($context);
	}

	public function getCurrentProduct()
    {       
        return $this->_registry->registry('current_product');
    }  

	public function getMyCustomMethod2()
	{
		// $this->AddProductDatabase->apibazaarvoice();
		$post = $this->_postFactory->create();
//		$collection = $post->getCollection()->getData();
		$collection = $post->getCollection();
		return $collection;
	}
}
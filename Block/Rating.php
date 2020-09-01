<?php

namespace Hunters\Bazaarvoice\Block;

class Rating extends \Magento\Framework\View\Element\Template
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

	public function getMyCustomRating()
	{
		$post = $this->_postFactory->create();
		$collection = $post->getCollection();
		return $collection;
	}
}
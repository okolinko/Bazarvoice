<?php

namespace Hunters\Bazaarvoice\Model;

class Post extends \Magento\Framework\Model\AbstractModel
{

	protected function _construct()
	{
		$this->_init('Hunters\Bazaarvoice\Model\ResourceModel\Post');
	}

}

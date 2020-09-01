<?php

namespace Hunters\Bazaarvoice\Model\ResourceModel\Post;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{

	/**
	 * Define resource model
	 *
	 * @return void
	 */
	protected function _construct()
	{
		$this->_init('Hunters\Bazaarvoice\Model\Post', 'Hunters\Bazaarvoice\Model\ResourceModel\Post');
	}

}

<?php

namespace  Hunters\Bazaarvoice\Model\ResourceModel;


class Post extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

	protected function _construct()
	{
		$this->_init('bazaarvoice_index', 'entity_id');
	}

}

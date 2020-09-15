<?php
namespace Hunters\Bazaarvoice\Controller\Page;

use Magento\Framework\Controller\ResultFactory;
use Hunters\Bazaarvoice\Block\Index;
use Hunters\Bazaarvoice\Service\AddProductDatabase;

class View extends \Magento\Framework\App\Action\Action
{
	/**
	 * @var \Magento\Framework\Controller\Result\JsonFactory
	 */
	protected $resultJsonFactory;
	protected $index;
	private $AddProductDatabase;
	/**
	 * @param \Magento\Framework\App\Action\Context $context
	 * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
	 */
	public function __construct(
		Index $index,
		AddProductDatabase  $AddProductDatabase,
		\Magento\Framework\App\Action\Context $context,
		\Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory)
	{
		$this->resultJsonFactory = $resultJsonFactory;
		$this->index = $index;
		$this->AddProductDatabase = $AddProductDatabase;
		parent::__construct($context);
	}
	/**
	 * View  page action
	 *
	 * @return \Magento\Framework\Controller\ResultInterface
	 */


	public function execute()
	{
		$result = $this->resultJsonFactory->create();
		if ($this->getRequest()->isAjax()) {
			$post = $this->getRequest()->getPost();
			$idComment = $post['id_comment'];
			$comment = $post['comment'];
            $nickname = $post['nickname'];
            if (empty($post['location'])){
            	$location = "TYT";
            }
            else{
            	$location = $post['location'];
            }
            $email = $post['email'];
            $sku = $post['sku'];
            $jsonType = $post['typeJson'];
            if ($jsonType = "submitreviewcomment.json"){
            	$res = $this->AddProductDatabase->submitreviewcomment($sku, $idComment, $comment, $nickname, $location, $email);
			return $result->setData($res);
            }
            if ($jsonType = "submitquestion.json"){
            	$res = $this->AddProductDatabase->submitquestion($sku, $comment, $nickname, $location, $email);
			return $result->setData($res);
            }
            if ($jsonType = "submitanswer.json"){
            	$res = $this->AddProductDatabase->submitanswer($sku, $idComment, $comment, $nickname, $location, $email);
			return $result->setData($res);
            }
		}
		$data = ['message' => 'In development!'];
		file_put_contents('/var/www/html/toppik-us-us.huntersconsult.com/var/log/bazar.log',  json_encode("TEST2") . "\n");
		return $result->setData($data);
	}
}
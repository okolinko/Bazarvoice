<?php

namespace Hunters\Bazaarvoice\Service;

use Magento\Framework\App\Action\Action;
use GuzzleHttp\ClientFactory;
use GuzzleHttp\Exception\GuzzleException;

class AddProductDatabase
{

	/**
	 * @var \Magento\Framework\App\ResourceConnection
	 */
	protected $connection;

	//Passkey=&Include=Answers&Filter=ProductId:FIBERS&Limit=10&Offset=10
	const API_REQUEST_URI = 'https://api.bazaarvoice.com/';
	private $clientFactory;

	public function __construct(
		\Magento\Framework\App\ResourceConnection $connection,
		ClientFactory $clientFactory
	)
	{
		$this->clientFactory = $clientFactory;
		$this->connection = $connection;
	}

	public function total($productSku, $jsonType){
		$client = $this->clientFactory->create(['config' => [
			'base_uri' => self::API_REQUEST_URI
		]]);
		$params = [
			'query' => [
				'ApiVersion' => '5.4',
				'Passkey' => 'caH2JOg8J7LWC1EAtswhl83KP40XRCPm1j2cjbvuLzxXM',
				'Include' => 'Answers',
				'Filter' => 'ProductId:'. $productSku
			]
		];
		$response = null;
		try {
			$response = $client->request(
				'GET',
				'data/'.$jsonType.'?',
				$params
			);

		} catch (GuzzleException $exception) {
			return $exception->getMessage();
		}
		$responseDataArray = json_decode($response->getBody()->getContents(), true);
		$total = $responseDataArray["TotalResults"];

		return $total;
	}

	public function addbazaarvoicequestions($productSku, $jsonType){
		$offset = 0;
		$client = $this->clientFactory->create(['config' => [
			'base_uri' => self::API_REQUEST_URI
		]]);
		$total = $this->total($productSku, $jsonType);
		$com_arr  = array();
		$i = 0;
		while ($total >= 0 ){
			$params = [
				'query' => [
					'ApiVersion' => '5.4',
					'Passkey' => 'caH2JOg8J7LWC1EAtswhl83KP40XRCPm1j2cjbvuLzxXM',
					'Include' => 'Answers',
					'Filter' => 'ProductId:'. $productSku,
					'Limit' => '10',
					'Offset' => $offset
				]
			];
			$response = null;
			try {
				$response = $client->request(
					'GET',
					'data/'.$jsonType.'?',
					$params
				);

			} catch (GuzzleException $exception) {
				return $exception->getMessage();
			}
			$info = json_decode($response->getBody()->getContents(), true);

			$result = $info['Results'];
			$p = 0;
			while($p <= 9)
			{
				if (!empty($result[$p]['Id'])) {
					$com_arr[$i]['id'] = $result[$p]['Id'];
					$com_arr[$i]['statistic'] = '';
					$com_arr[$i]['sku'] = $result[$p]['ProductId'];
					$com_arr[$i]['data'] = $result[$p]['SubmissionTime'];
					$com_arr[$i]['type_text'] = 'questions';
					$com_arr[$i]['context_data_values'] = '';
					$com_arr[$i]['text'] = $result[$p]['QuestionSummary'];
					$com_arr[$i]['title_text'] = '';
					$com_arr[$i]['name'] = $result[$p]['UserNickname'];
					if (!empty($result[$p]['Photos'])){
						$com_arr[$i]['photo'] = $result[$p]['Photos'][0];
					}
					else{
						$com_arr[$i]['photo'] = '';
					}
					$com_arr[$i]['advice_use'] = '';
					$com_arr[$i]['address'] = $result[$p]['UserLocation'];
					$com_arr[$i]['recommend'] = '';
					$com_arr[$i]['rating'] = '';
					$i++;
				}
				$p++;
			}
			$total -= 10;
			$offset += 10;
		}

		$connection = $this->connection->getConnection();
		$table = $this->connection->getTableName('bazaarvoice_index');
		$connection->insertMultiple($table, $com_arr);
		return $com_arr;
	}

	public function addbazaarvoiceanswers($productSku, $jsonType){
		$offset = 0;
		$client = $this->clientFactory->create(['config' => [
			'base_uri' => self::API_REQUEST_URI
		]]);
		$total = $this->total($productSku, $jsonType);
		$com_arr  = array();
		$i = 0;
		while ($total >= 0 ){
			$params = [
				'query' => [
					'ApiVersion' => '5.4',
					'Passkey' => 'caH2JOg8J7LWC1EAtswhl83KP40XRCPm1j2cjbvuLzxXM',
					'Include' => 'Answers',
					'Filter' => 'ProductId:'. $productSku,
					'Limit' => '10',
					'Offset' => $offset
				]
			];
			$response = null;
			try {
				$response = $client->request(
					'GET',
					'data/'.$jsonType.'?',
					$params
				);

			} catch (GuzzleException $exception) {
				return $exception->getMessage();
			}
			$info = json_decode($response->getBody()->getContents(), true);

			$result = $info['Results'];
			$p = 0;
			while($p <= 9)
			{
				if (!empty($result[$p]['Id'])) {
					$com_arr[$i]['id'] = $result[$p]['QuestionId'];
					$com_arr[$i]['statistic'] = '';
					$com_arr[$i]['sku'] = $productSku;
					$com_arr[$i]['data'] = $result[$p]['SubmissionTime'];
					$com_arr[$i]['type_text'] = 'answers';
					$com_arr[$i]['context_data_values'] = '';
					$com_arr[$i]['text'] = $result[$p]['AnswerText'];
					$com_arr[$i]['title_text'] = '';
					$com_arr[$i]['name'] = $result[$p]['UserNickname'];
					if (!empty($result[$p]['Photos'])){
						$com_arr[$i]['photo'] = $result[$p]['Photos'][0];
					}
					else{
						$com_arr[$i]['photo'] = '';
					}
					$com_arr[$i]['advice_use'] = '';
					$com_arr[$i]['address'] = $result[$p]['UserLocation'];
					$res = array();
    				$positive = array('yes' => $result[$p]['TotalPositiveFeedbackCount']);
    				$negative = array('no' => $result[$p]['TotalNegativeFeedbackCount']);
    				$res = array_merge($res, $negative, $positive);
					$com_arr[$i]['recommend'] = json_encode($res);
					$com_arr[$i]['rating'] = '';
					$i++;
				}
				$p++;
			}
			$total -= 10;
			$offset += 10;
		}

		$connection = $this->connection->getConnection();
		$table = $this->connection->getTableName('bazaarvoice_index');
		$connection->insertMultiple($table, $com_arr);
		return $com_arr;
	}

	public function addbazaarvoicereviews($productSku, $jsonType){
		$offset = 0;
		$client = $this->clientFactory->create(['config' => [
			'base_uri' => self::API_REQUEST_URI
		]]);
		$total = $this->total($productSku, $jsonType);
		$com_arr  = array();
		$i = 0;
		while ($total >= 0 ){
			$params = [
				'query' => [
					'ApiVersion' => '5.4',
					'Passkey' => 'caH2JOg8J7LWC1EAtswhl83KP40XRCPm1j2cjbvuLzxXM',
					'Include' => 'Answers',
					'Filter' => 'ProductId:'. $productSku,
					'Limit' => '10',
					'Offset' => $offset
				]
			];
			$response = null;
			try {
				$response = $client->request(
					'GET',
					'data/'.$jsonType.'?',
					$params
				);

			} catch (GuzzleException $exception) {
				return $exception->getMessage();
			}
			$info = json_decode($response->getBody()->getContents(), true);

			$result = $info['Results'];
			$p = 0;
			while($p <= 9)
			{
				if (!empty($result[$p]['Id'])) {
					$com_arr[$i]['id'] = $result[$p]['Id'];
					if (!empty($result[$p]['IsRecommended'])){
						$com_arr[$i]['statistic'] = json_encode($result[$p]['SecondaryRatings'] += ['Review' => intval($result[$p]['IsRecommended']), 'Votes' => $result[$p]['TotalFeedbackCount']]);
					}
					else{
						$com_arr[$i]['statistic'] = json_encode($result[$p]['SecondaryRatings'] += ['Review' => 0, 'Votes' => $result[$p]['TotalFeedbackCount']]);
					}
					$com_arr[$i]['sku'] = $result[$p]['ProductId'];
					$com_arr[$i]['data'] = $result[$p]['SubmissionTime'];
					$com_arr[$i]['type_text'] = 'reviews';
					$com_arr[$i]['context_data_values'] = json_encode($result[$p]['ContextDataValues']);
					if (!empty($result[$p]['ReviewText'])){
						$com_arr[$i]['text'] = $result[$p]['ReviewText'];
					}
					else{
						$com_arr[$i]['text'] = '';
					}
					$com_arr[$i]['title_text'] = $result[$p]['Title'];
					$com_arr[$i]['name'] = $result[$p]['UserNickname'];
					if (!empty($result[$p]['Photos'])){
						$com_arr[$i]['photo'] = json_encode($result[$p]['Photos']);
					}
					else{
						$com_arr[$i]['photo'] = '';
					}
					if (!empty($result[$p]['AdditionalFields']['HowUsed']['Value'])){
						$com_arr[$i]['advice_use'] = $result[$p]['AdditionalFields']['HowUsed']['Value'];
					}
					else{
						$com_arr[$i]['advice_use'] = '';
					}
					$com_arr[$i]['address'] = $result[$p]['UserLocation'];

					$res = array();
    				if (!empty($result[$p]['TagDimensions'])){
						$res = $result[$p]['TagDimensions'];
					}
    				if (!empty($result[$p]['AdditionalFields'])){
    					$res = array_merge($res, $result[$p]['AdditionalFields']);
					}
    				if ($result[$p]['IsRecommended'] == 1){
    					$recomend = array('recomend' => 'I recommend this product.');
    					$res = array_merge($res, $recomend);
					}
    				else {
						$recomend = array('recomend' => 'I do not recommend this product.');
						$res = array_merge($res, $recomend);
					}
    				if (!empty($result[$p]['TotalNegativeFeedbackCount'])){
    					$negative = array('negative' => $result[$p]['TotalNegativeFeedbackCount']);
						$res = array_merge($res, $negative);
					}
    				else {
						$negative = array('negative' => '0');
						$res = array_merge($res, $negative);
					}
					if (!empty($result[$p]['TotalPositiveFeedbackCount'])){
						$positive = array('positive' => $result[$p]['TotalPositiveFeedbackCount']);
						$res = array_merge($res, $positive);
					}
					else {
						$positive = array('positive' => '0');
						$res = array_merge($res, $positive);
					}				
					$com_arr[$i]['recommend'] = json_encode($res);
					$com_arr[$i]['rating'] = $result[$p]['Rating'];
					$i++;
				}
				$p++;
			}
			$total -= 10;
			$offset += 10;
		}

		$connection = $this->connection->getConnection();
		$table = $this->connection->getTableName('bazaarvoice_index');
		$connection->insertMultiple($table, $com_arr);
		return $com_arr;
	}

	public function addbazaarvoicereviewcomments($productSku, $jsonType){
		$offset = 0;
		$client = $this->clientFactory->create(['config' => [
			'base_uri' => self::API_REQUEST_URI
		]]);
		$total = $this->total($productSku, $jsonType);
		$com_arr  = array();
		$i = 0;
		while ($total >= 0 ){
			$params = [
				'query' => [
					'ApiVersion' => '5.4',
					'Passkey' => 'caH2JOg8J7LWC1EAtswhl83KP40XRCPm1j2cjbvuLzxXM',
					'Include' => 'Answers',
					'Filter' => 'ProductId:'. $productSku,
					'Limit' => '10',
					'Offset' => $offset
				]
			];
			$response = null;
			try {
				$response = $client->request(
					'GET',
					'data/'.$jsonType.'?',
					$params
				);

			} catch (GuzzleException $exception) {
				return $exception->getMessage();
			}
			$info = json_decode($response->getBody()->getContents(), true);

			$result = $info['Results'];
			$p = 0;
			while($p <= 9)
			{
				if (!empty($result[$p]['Id'])) {
					$com_arr[$i]['id'] = $result[$p]['ReviewId'];
					$com_arr[$i]['statistic'] = '';
					$com_arr[$i]['sku'] = $productSku;
					$com_arr[$i]['data'] = $result[$p]['SubmissionTime'];
					$com_arr[$i]['type_text'] = 'reviewcomments';
					$com_arr[$i]['context_data_values'] = '';
					if (!empty($result[$p]['CommentText'])){
						$com_arr[$i]['text'] = $result[$p]['CommentText'];
					}
					else{
						$com_arr[$i]['text'] = '';
					}
					$com_arr[$i]['title_text'] = '';
					$com_arr[$i]['name'] = $result[$p]['UserNickname'];
					if (!empty($result[$p]['Photos'])){
						$com_arr[$i]['photo'] = json_encode($result[$p]['Photos']);
					}
					else{
						$com_arr[$i]['photo'] = '';
					}
					$com_arr[$i]['advice_use'] = '';
					$com_arr[$i]['address'] = $result[$p]['UserLocation'];
					$com_arr[$i]['recommend'] = '';
					$com_arr[$i]['rating'] = '';
					$i++;
				}
				$p++;
			}
			$total -= 10;
			$offset += 10;
		}

		$connection = $this->connection->getConnection();
		$table = $this->connection->getTableName('bazaarvoice_index');
		$connection->insertMultiple($table, $com_arr);
		return $com_arr;
	}

	public function addbazaarvoiceproducts($productSku, $jsonType){
		$client = $this->clientFactory->create(['config' => [
			'base_uri' => self::API_REQUEST_URI
		]]);
		$i = 0;
		$params = [
			'query' => [
				'ApiVersion' => '5.4',
				'Passkey' => 'caH2JOg8J7LWC1EAtswhl83KP40XRCPm1j2cjbvuLzxXM',
				'Filter' => 'Id:'. $productSku,
				'stats' => 'reviews'
			]
		];
		$response = null;
		try {
			$response = $client->request(
				'GET',
				'data/'.$jsonType.'?',
				$params
			);
		} catch (GuzzleException $exception) {
			return $exception->getMessage();
		}
		$info = json_decode($response->getBody()->getContents(), true);
		$result = $info['Results'];
		$com_arr[$i]['id'] = '';
		$com_arr[$i]['statistic'] = json_encode($result[0]['ReviewStatistics']);
		$com_arr[$i]['sku'] =  $result[0]['Id'];
		$com_arr[$i]['data'] = '';
		$com_arr[$i]['type_text'] = 'product';
		$com_arr[$i]['context_data_values'] = '';
		$com_arr[$i]['text'] = '';
		$com_arr[$i]['title_text'] = '';
		$com_arr[$i]['name'] = $result[0]['Name'];
		$com_arr[$i]['photo'] = '';
		$com_arr[$i]['advice_use'] = '';
		$com_arr[$i]['address'] = '';
		$com_arr[$i]['recommend'] = '';
		$com_arr[$i]['rating'] = '';

		$connection = $this->connection->getConnection();
		$table = $this->connection->getTableName('bazaarvoice_index');
		$connection->insertMultiple($table, $com_arr);
		return $com_arr;
	}

	public function help($productSku){
		$result = $this->addbazaarvoicequestions($productSku, "questions.json");
		$result2 = $this->addbazaarvoiceanswers($productSku, "answers.json");
		$result3 = $this->addbazaarvoicereviews($productSku, "reviews.json");
		$result4 = $this->addbazaarvoicereviewcomments($productSku, "reviewcomments.json");
		$result5 = $this->addbazaarvoiceproducts($productSku, "products.json");
	}

	public function submitreviewcomment($productSku, $idComment, $comment, $nickname, $location, $email){
		$client = $this->clientFactory->create(['config' => [
			'base_uri' => self::API_REQUEST_URI
		]]);
		$jsonType = "submitreviewcomment.json";
		$params = [
			'headers' => [
				'Content-Type: application/x-www-form-urlencoded'
			],
			'query' => [
				'ApiVersion' => '5.4',
				'Passkey' => 'caXxEJtdQlYP3kdQvuL2rX3tggBti9F4K0GdWh7HWNNb4',
				'ReviewId' => $idComment,
				'Action' => 'submit',
				'CommentText'=> $comment,
				'UserEmail' => $email,
				'UserNickname' => $nickname,
				'UserLocation' => $location
			]
		];
		$response = null;
		try {
			$response = $client->request(
				'POST',
				'data/'.$jsonType.'?',
				$params
			);

		} catch (GuzzleException $exception) {
			return $exception->getMessage();
		}
		$responseDataArray = json_decode($response->getBody()->getContents(), true);

		return $responseDataArray;
	}

	public function submitquestion($productSku, $questionSummary, $nickname, $location, $email){
		$client = $this->clientFactory->create(['config' => [
			'base_uri' => self::API_REQUEST_URI
		]]);
		$jsonType = "submitquestion.json";
		$params = [
			'headers' => [
				'Content-Type: application/x-www-form-urlencoded'
			],
			'query' => [
				'ApiVersion' => '5.4',
				'Passkey' => 'caXxEJtdQlYP3kdQvuL2rX3tggBti9F4K0GdWh7HWNNb4',
				'ProductId' => $productSku,
				'Action' => 'submit',
				'QuestionSummary'=> $questionSummary,
				'UserEmail' => $email,
				'UserNickname' => $nickname,
				'UserLocation' => $location
			]
		];
		$response = null;
		try {
			$response = $client->request(
				'POST',
				'data/'.$jsonType.'?',
				$params
			);

		} catch (GuzzleException $exception) {
			return $exception->getMessage();
		}
		$responseDataArray = json_decode($response->getBody()->getContents(), true);

		return $responseDataArray;
	}

	public function submitanswer($productSku, $idQuestion, $answer, $nickname, $location, $email){
		$client = $this->clientFactory->create(['config' => [
			'base_uri' => self::API_REQUEST_URI
		]]);
		$jsonType = "submitanswer.json";
		$params = [
			'headers' => [
				'Content-Type: application/x-www-form-urlencoded'
			],
			'query' => [
				'ApiVersion' => '5.4',
				'Passkey' => 'caXxEJtdQlYP3kdQvuL2rX3tggBti9F4K0GdWh7HWNNb4',
				'QuestionId' => $idQuestion,
				'Action' => 'submit',
				'AnswerText'=> $answer,
				'UserEmail' => $email,
				'UserNickname' => $nickname,
				'UserLocation' => $location
			]
		];
		$response = null;
		try {
			$response = $client->request(
				'POST',
				'data/'.$jsonType.'?',
				$params
			);

		} catch (GuzzleException $exception) {
			return $exception->getMessage();
		}
		$responseDataArray = json_decode($response->getBody()->getContents(), true);

		return $responseDataArray;
	}

	public function apibazaarvoice(){
		$connection = $this->connection->getConnection();
		$table = $this->connection->getTableName('bazaarvoice_index');
		$connection->truncateTable($table);
		$res = ["PKIT20010", "FIBERS", "FHS4B", "TPRT", "STR", "BBF", "PKIT20020", "TSA4C", "THT", "TTS", "TSH3", "TCD2", "SC3", "OPTE4"];
		array_map(array($this, 'help'), $res);
		return "DONE";
	}
}
<?php
/**
 * Date: 26/02/15
 * Time: 12:23
 */

namespace Muzar\BazaarBundle\Suggestion;


use Elastica\Client;
use Elastica\Query;
use Elastica\Request;

class ElasticaQuerySuggester implements QuerySuggesterInterface
{

	/** @var Client */
	protected $client;

	protected $index;

	protected $type;

	function __construct(Client $client, $index, $type)
	{
		$this->client = $client;
		$this->index = $index;
		$this->type = $type;
	}


	/**
	 * Add query to index
	 *
	 * @param $query
	 * @param int $usages
	 * @return mixed
	 */
	public function add($query, $usages = 1)
	{

		$data = array(
			'script' => 'ctx._source.suggest.weight += usages',
			'params' => array(
				'usages' => $usages,
			),
			'upsert' => array(
				'query' => $query,
				'suggest' => array(
					'input' => $this->getInputFromQuery($query),
					'output' => $query,
					'weight' => $usages,
				)
			),
		);
		$this->client->updateDocument($this->createId($query), $data, $this->index, $this->type);
	}

	/**
	 * Get query suggestions based on query
	 * http://www.elasticsearch.org/guide/en/elasticsearch/reference/current/search-suggesters-completion.html
	 *
	 * @param $query
	 * @param int $limit
	 * @return string[]
	 */
	public function get($query, $limit = 5)
	{

		$index = $this->client->getIndex($this->index);

		$suggestType = $this->type . '-' . 'suggest';

		$query = array(
			$suggestType => array(
				'text' => $query,
				'completion' => array(
					'field' => 'suggest',
					'fuzzy' => true,
				),
			),
		);

		$response = $index->request('_suggest', Request::GET, $query, array(
			'limit' => $limit
		));

		$data = $response->getData();
		if (empty($data[$suggestType][0]['options']))
		{
			return array();
		}

		return array_map(function($result) {
			return $result['text'];
		}, $data[$suggestType][0]['options']);
	}

	protected function getInputFromQuery($query)
	{
		return array_filter(array_map('trim', explode(' ', $query)));
	}

	protected function createId($query)
	{
		return sha1($query);
	}

}
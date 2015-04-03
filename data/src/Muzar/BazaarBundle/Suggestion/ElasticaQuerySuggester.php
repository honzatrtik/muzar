<?php
/**
 * Date: 26/02/15
 * Time: 12:23
 */

namespace Muzar\BazaarBundle\Suggestion;


use Elastica\Client;
use Elastica\Query;
use Elastica\Request;
use Elastica\Result;

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

		if (empty($query))
		{
			throw new \InvalidArgumentException('Query must not be empty.');
		}

		$data = array(
			'script' => 'ctx._source.weight += usages',
			'params' => array(
				'usages' => $usages,
			),
			'upsert' => array(
				'query' => $query,
				'weight' => $usages,
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

		$matchQuery = new Query\Match();
		$matchQuery->setFieldQuery('query', $query)
			->setFieldOperator('query', 'or');

		$functionScoreQuery = new Query\FunctionScore();
		$functionScoreQuery->setQuery($matchQuery)
			->setParam('field_value_factor', array(
				'field' => 'weight',
				'factor' => 1,
				'modifier' => 'ln1p',
			));


		$q = new Query($functionScoreQuery);
		$q->setSize($limit);

		$resultSet = $index->search($q);
		$results = $resultSet->getResults();

		return array_map(function(Result $r) {
			$s = $r->getSource();
			return $s['query'];
		}, $results);


	}

	protected function createId($query)
	{
		return sha1($query);
	}

}
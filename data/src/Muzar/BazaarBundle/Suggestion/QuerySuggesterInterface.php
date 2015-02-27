<?php
/**
 * Date: 24/02/15
 * Time: 17:10
 */

namespace Muzar\BazaarBundle\Suggestion;


interface QuerySuggesterInterface
{
	/**
	 * Add query to index
	 *
*@param $query
	 * @param int $usages
	 * @return mixed
	 */
	public function add($query, $usages = 1);

	/**
	 * Get query suggestions based on query
	 * @param $query
	 * @return string[]
	 */
	public function get($query, $limit = 5);


}
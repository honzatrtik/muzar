<?php
/**
 * Date: 17/03/15
 * Time: 20:48
 */

namespace Muzar\BazaarBundle\Entity;

use Symfony\Component\PropertyAccess\Exception\NoSuchPropertyException;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\PropertyAccess\PropertyPath;

class Utils
{

	const ARRAY_PATH_SEPARATOR = '.';


	/** @var  PropertyAccessorInterface */
	protected $accessor;


	/**
	 * Utils constructor.
	 *
	 * @param PropertyAccessorInterface $accessor
	 */
	public function __construct(PropertyAccessorInterface $accessor)
	{
		$this->accessor = $accessor;
	}


	public function fromArray($entity, array $array)
	{
		$flatten = $this->flattenArray($array);
		foreach($flatten as $path => $value)
		{
			$this->accessor->setValue($entity, $path, $value);
		}
	}


	public function flattenArray(array $array, array $initialPath = array(), array & $result = array())
	{
		foreach($array as $key => $value)
		{
			$path = $initialPath;
			array_push($path, $key);

			if (is_array($value))
			{
				static::flattenArray($value, $path, $result);
			}
			else
			{
				$result[join('.',$path)] = $value;
			}
		}
		return $result;
	}
}
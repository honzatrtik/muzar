<?php
/**
 * Date: 20/05/15
 * Time: 13:46
 */

namespace Muzar\BazaarBundle\Filesystem;


use League\Flysystem\FilesystemInterface;

class GarbageCollector implements GarbageCollectorInterface
{

	const DEFAULT_THRESHOLD = '+2 HOURS';


	private $threshold;

	/**
	 * GarbageCollector constructor.
	 *
	 * @param $threshold
	 */
	public function __construct($threshold = self::DEFAULT_THRESHOLD)
	{
		$this->threshold = $threshold;
	}


	public function run(FilesystemInterface $fs, $directory = '')
	{
		foreach($fs->listContents($directory, TRUE) as $file)
		{
			if ($file['basename'][0] === '.' || $file['type'] !== 'file')
			{
				continue;
			}

			if (strtotime($this->threshold, $file['timestamp']) < time())
			{
				$fs->delete($file['path']);
			}
		}
	}
}
<?php
/**
 * Date: 06/03/15
 * Time: 12:45
 */

namespace Muzar\BazaarBundle\Command;

use League\Flysystem\FilesystemInterface;
use Muzar\BazaarBundle\Filesystem\GarbageCollectorInterface;
use Predis\Client;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MediaDirCleanCommand extends ContainerAwareCommand
{

	/** @var FilesystemInterface[] */
	protected $filesystems = array();

	/** @var GarbageCollectorInterface  */
	protected $gc;

	public function __construct(GarbageCollectorInterface $gc, array $filesystems)
	{
		foreach($filesystems as $filesystem)
		{
			if (!($filesystem instanceof FilesystemInterface))
			{
				throw new \InvalidArgumentException();
			}
		}

		$this->filesystems = $filesystems;
		$this->gc = $gc;

		parent::__construct();
	}


	protected function configure()
	{
		$this
			->setName('media-dir:clean')
			->setDescription('Clean media directories.');
    }

	protected function execute(InputInterface $input, OutputInterface $output)
	{
		foreach($this->filesystems as $fs)
		{
			$this->gc->run($fs);
		}
		$output->writeln('Media directories cleaned.');


	}
}
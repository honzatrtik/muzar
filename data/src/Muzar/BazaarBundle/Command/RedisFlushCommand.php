<?php
/**
 * Date: 06/03/15
 * Time: 12:45
 */

namespace Muzar\BazaarBundle\Command;

use Predis\Client;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RedisFlushCommand extends ContainerAwareCommand
{

	/** @var  Client  */
	protected $redis;

	/**
	 * RebuildDataCommand constructor.
	 *
	 * @param Client $redis
	 */
	public function __construct(Client $redis)
	{
		$this->redis = $redis;
		parent::__construct();
	}


	protected function configure()
	{
		$this
			->setName('redis:flush')
			->setDescription('Flush redis.');
    }

	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$this->redis->flushdb();
		$output->writeln('Redis db flushed.');
	}
}
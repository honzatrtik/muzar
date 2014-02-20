<?php
/**
 * Date: 26/11/13
 * Time: 17:47
 */

namespace Muzar\ScraperBundle\Command;


use Doctrine\ORM\EntityManager;
use Goutte\Client;
use Muzar\ScraperBundle\Entity\Ad;
use Muzar\ScraperBundle\Entity\AdProperty;
use Muzar\ScraperBundle\HtmlParser\HtmlParserInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ParseCommand extends ContainerAwareCommand
{

	const MAX_LINKS = 10;
	const USLEEP_DELAY = 50;


	/**
	 * @var EntityManager
	 */
	protected $em;

	/**
	 * @var Client
	 */
	protected $client;

	/** @var HtmlParserInterface[]  */
	protected $parsers = array();


	function __construct(EntityManager $em, Client $client, array $parsers)
	{
		$this->client = $client;
		$this->em = $em;
		foreach($parsers as $name => $parser)
		{
			$this->addParser($name, $parser);
		}

		parent::__construct();
	}


	protected function addParser($name, HtmlParserInterface $parser)
	{
		$this->parsers[$name] = $parser;
		return $this;
	}

	/**
	 * @param $name
	 * @return HtmlParserInterface
	 * @throws \OutOfBoundsException
	 */
	protected function getParser($name)
	{
		if (!isset($this->parsers[$name]))
		{
			throw new \OutOfBoundsException(sprintf('Parser for source "%s" is not set.', $name));
		}
		return $this->parsers[$name];
	}


	protected function configure()
	{
		$this
			->setName('muzar:scraper:parse')
			->addOption('max', 'm', InputOption::VALUE_OPTIONAL, 'Max links to parse.', self::MAX_LINKS)
			->setDescription('Scrape data from scraped links.')
		;
	}


	protected function execute(InputInterface $input, OutputInterface $output)
	{

		$max = $input->getOption('max');

		$goutte = $this->client;
		$em = $this->em;

		/** @var \Muzar\ScraperBundle\Entity\AdRepository $repository */
		$repository = $em->getRepository('\Muzar\ScraperBundle\Entity\Ad');

		$count = $repository->getQueryUnparsed()->select('COUNT(a.id)')->getQuery()->getSingleScalarResult();
		$output->writeln(sprintf('<comment>%d unparsed links found!</comment>', $count));

		/** @var Ad[] $ads */
		$ads = $repository->getQueryUnparsed()->setMaxResults($max)->getQuery()->getResult();
		foreach($ads as $ad)
		{
			$ad->setParsed(new \DateTime());
			$ad->setStatus(0);

			try
			{
				$parser = $this->getParser($ad->getSource());
				$crawler = $goutte->request('GET', $ad->getLink());
				$params = $parser->parse($crawler);
				foreach(array_filter($params) as $name => $value)
				{
					$ad->addPropertyByName($name, $value);
				}

				$ad->setStatus(1);
				$em->persist($ad);

				$output->writeln(sprintf('<info>Parsed: "%s"</info>', $ad->getLink()));
			}
			catch(\Exception $e)
			{
				$output->writeln(sprintf('<error>%s</error>', $e->getMessage()));
			}

			usleep(self::USLEEP_DELAY);
		}

		$em->flush();

	}





}
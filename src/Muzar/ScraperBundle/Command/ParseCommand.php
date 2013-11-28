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
use Muzar\ScraperBundle\HtmlParser;
use Muzar\ScraperBundle\RssLinkIterator;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ParseCommand extends ContainerAwareCommand
{

	const MAX_LINKS = 10;
	const USLEEP_DELAY = 50;

	protected function configure()
	{
		$this
			->setName('muzar:scraper:parse')
			->addOption('max', 'm', InputOption::VALUE_OPTIONAL, 'Max links to parse.', self::MAX_LINKS)
			->setDescription('Scrape data from links scraped from hudebnibazar.cz')
		;
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{

		$max = $input->getOption('max');

		/** @var Client $goutte */
		$goutte = $this->getContainer()->get('goutte');

		/** @var HtmlParser $parser */
		$parser = $this->getContainer()->get('muzar_scraper.parser');

		/** @var EntityManager $em */
		$em = $this->getContainer()->get('doctrine')->getManager();

		/** @var \Muzar\ScraperBundle\Repository\Ad $repository */
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
				// Zjitime si edit link
				parse_str(parse_url($ad->getLink(), PHP_URL_QUERY), $vars);
				$id = $vars['ID'];

				$link = 'http://hudebnibazar.cz/formular.php?ID=' . $id;

				$crawler = $goutte->request('GET', $link);
				$params = $parser->parse($crawler);
				foreach($params as $name => $value)
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
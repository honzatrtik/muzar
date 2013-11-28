<?php
/**
 * Date: 26/11/13
 * Time: 17:47
 */

namespace Muzar\ScraperBundle\Command;


use Doctrine\ORM\EntityManager;
use Muzar\ScraperBundle\Entity\Ad;
use Muzar\ScraperBundle\RssLinkIterator;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class LinksCommand extends ContainerAwareCommand
{

	const RSS_URL = 'http://hudebnibazar.cz/rss/rss.php';

	protected function configure()
	{
		$this
			->setName('muzar:scraper:links')
			->setDescription('Scrape links from hudebnibazar.cz RSS.')
		;
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{

		/** @var EntityManager $em */
		$em = $this->getContainer()->get('doctrine')->getManager();
		$it = new RssLinkIterator('http://hudebnibazar.cz/rss/rss.php');

		$repository = $em->getRepository('\Muzar\ScraperBundle\Entity\Ad');

		foreach($it as $link => $date)
		{

			// Jen pokud uz link nemame
			if (!$repository->findOneBy(array('link' => $link)))
			{
				$ad = new Ad();
				$ad->setLink($link);
				$ad->setCreated($date);

				$em->persist($ad);
				$output->writeln(sprintf('<info>Added: %s</info>', $link));
			}


		}


		$em->flush();

	}
}
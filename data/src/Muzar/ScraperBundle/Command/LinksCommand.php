<?php
/**
 * Date: 26/11/13
 * Time: 17:47
 */

namespace Muzar\ScraperBundle\Command;


use Doctrine\ORM\EntityManager;
use Muzar\ScraperBundle\Entity\Ad;
use Muzar\ScraperBundle\LinkIterator\Hudebnibazar;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class LinksCommand extends ContainerAwareCommand
{

	protected $linkIterators = array();

	/** @var  EntityManager */
	protected $em;


	function __construct(EntityManager $em, $linkIterators)
	{
		$this->em = $em;
		foreach($linkIterators as $name => $it)
		{
			$this->addLinkIterator($name, $it);
		}

		parent::__construct();
	}

	protected function addLinkIterator($name, \Iterator $it)
	{
		$this->linkIterators[$name] = $it;
		return $this;
	}

	protected function configure()
	{
		$this
			->setName('muzar:scraper:links')
			->setDescription('Scrape links from configured link iterators.')
		;
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{

		$repository = $this->em->getRepository('\Muzar\ScraperBundle\Entity\Ad');
		foreach($this->linkIterators as $source => $it)
		{

			foreach($it as $link => $date)
			{

				// Jen pokud uz link nemame
				if (!$repository->findOneBy(array('link' => $link)))
				{
					$ad = new Ad();
					$ad->setLink($link);
					$ad->setCreated($date);
					$ad->setSource($source);
					$this->em->persist($ad);
					$output->writeln(sprintf('<info>Added: %s</info>', $link));
				}


			}
		}


		$this->em->flush();

	}
}
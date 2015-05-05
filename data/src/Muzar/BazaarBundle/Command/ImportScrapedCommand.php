<?php
/**
 * Date: 06/03/15
 * Time: 12:45
 */

namespace Muzar\BazaarBundle\Command;

use Doctrine\ORM\EntityManager;
use Muzar\BazaarBundle\Importer\Hudebnibazar;
use Muzar\BazaarBundle\Importer\ImporterInterface;
use Muzar\BazaarBundle\Media\ItemMediaFactoryInterface;
use Muzar\ScraperBundle\Entity\Ad;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ImportScrapedCommand extends ContainerAwareCommand
{

	/** @var  EntityManager */
	protected $em;

	/** @var ImporterInterface  */
	protected $importer;

	function __construct(EntityManager $em, ImporterInterface $importer)
	{
		$this->em = $em;
		$this->importer = $importer;
		parent::__construct();
	}


	protected function configure()
	{
		$this
			->setName('muzar:import-scraped')
			->setDescription('Imports items from scraped ad sites.');
    }

	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$ads = $this->em->createQueryBuilder()
			->select('a', 'p')
			->from('Muzar\ScraperBundle\Entity\Ad', 'a')
			->join('a.properties', 'p')
			->where('a.source = :source')
			->setParameter('source', 'hudebnibazar')
			->getQuery()
			->getResult();

		/** @var Ad $ad */
		foreach($ads as $ad)
		{
			$item = $this->importer->createItem($ad);
			if ($item === NULL)
			{
				$ad->setStatus(Ad::STATUS_IMPORT_FAILURE);
				$this->em->persist($ad);
				$this->em->flush();
				continue;
			}

			$ad->setStatus(Ad::STATUS_IMPORT_SUCCESS);

			$this->em->persist($item);
			$this->em->persist($ad);
			$this->em->flush();


			$i = 0;
			$urls = $ad->getPropertyValueByName('imageUrls') ?: array();
			var_dump($urls);
			foreach($urls as $url)
			{
				$name = $i . '-' . pathinfo($url, PATHINFO_BASENAME);
				$item->getItemMedia()->add($name, $url);
				$i++;
			}
		}
	}
}
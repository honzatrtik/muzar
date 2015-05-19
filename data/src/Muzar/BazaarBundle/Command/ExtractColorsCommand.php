<?php
/**
 * Date: 06/03/15
 * Time: 12:45
 */

namespace Muzar\BazaarBundle\Command;

use Doctrine\ORM\EntityManager;
use Muzar\BazaarBundle\ColorExtractor\ColorExtractorInterface;
use Muzar\BazaarBundle\Entity\Item;
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

class ExtractColorsCommand extends ContainerAwareCommand
{

	/** @var  EntityManager */
	protected $em;

	/** @var ColorExtractorInterface  */
	protected $extractor;

	/**
	 * ExtractColorsCommand constructor.
	 *
	 * @param EntityManager $em
	 * @param ColorExtractorInterface $extractor
	 */
	public function __construct(EntityManager $em, ColorExtractorInterface $extractor)
	{
		$this->em = $em;
		$this->extractor = $extractor;
		parent::__construct();
	}


	protected function configure()
	{
		$this
			->setName('muzar:extract-colors')
			->setDescription('Update items background colors from front image.');
    }

	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$items = $this->em->createQueryBuilder()
			->select('i')
			->from('Muzar\BazaarBundle\Entity\Item', 'i')
			->where('i.backgroundColor IS NULL')
			->getQuery()
			->getResult();


		$i = 0;

		/** @var Item $item */
		foreach($items as $item)
		{
			$i++;
			if ($urls = $item->getImageUrls())
			{
				$url = $urls[0];
				$url .= '?variant=thumb';

				$color = $this->extractor->extract($url);
				$item->setBackgroundColor($color);

				$output->writeln(sprintf('Extracted <info>"%s"</info> from "%s"', $color, $url));

				$this->em->persist($item);
			}

			if (!($i % 100))
			{
				$this->em->flush();
			}

		}
		$this->em->flush();
	}
}
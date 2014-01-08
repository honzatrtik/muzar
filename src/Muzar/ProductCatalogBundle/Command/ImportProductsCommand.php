<?php
/**
 * Date: 26/11/13
 * Time: 17:47
 */

namespace Muzar\ProductCatalogBundle\Command;


use Doctrine\ORM\EntityManager;
use Muzar\ProductCatalogBundle\Entity\Category;
use Muzar\ProductCatalogBundle\Entity\Product;
use Muzar\ProductCatalogBundle\Importer\ProductXmlIterator;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ImportProductsCommand extends ContainerAwareCommand
{

	const DEFAULT_XML_URL = 'http://kytary.cz/resources/xmlfeed/xmlfeedSeznam3971.xml';

	protected $xmlUrl;

	function __construct($xmlUrl = self::DEFAULT_XML_URL)
	{
		$this->xmlUrl = $xmlUrl;
		parent::__construct();
	}


	protected function configure()
	{
		$this
			->setName('muzar:product-catalog:import')
			->setDescription(sprintf('Import products from "%s."', $this->xmlUrl));
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{

		/** @var EntityManager $em */
		$em = $this->getContainer()->get('doctrine')->getManager();

		// Vypneme logger, jinak spotrebovavame mrdu pameti
		$em->getConnection()->getConfiguration()->setSQLLogger(NULL);

		$it = new ProductXmlIterator($this->xmlUrl);

		$repository = $em->getRepository('\Muzar\ProductCatalogBundle\Entity\Product');
		$categoryRepository = $em->getRepository('\Muzar\ProductCatalogBundle\Entity\Category');

		$i = 0;

		foreach($it as $data)
		{

			if (!$product = $repository->findOneBy(array('name' => $data['PRODUCT'])))
			{
				$product = new Product();
				$output->writeln(sprintf('<info>New product: "%s"</info>', $data['PRODUCT']));
			}
			else
			{
				$output->writeln(sprintf('<info>Updating product: "%s"</info>', $data['PRODUCT']));
			}

			$em->persist($product);
			$product
				->setSource('kytary.cz')
				->setName(empty($data['PRODUCT']) ? NULL : $data['PRODUCT'])
				->setDescription(empty($data['DESCRIPTION']) ? NULL : $data['DESCRIPTION'])
				->setEan(empty($data['EAN']) ? NULL : $data['EAN'])
				->setImageUrl(empty($data['IMGURL']) ? NULL : $data['IMGURL'])
				->setUrl(empty($data['URL']) ? NULL : $data['URL'])
				->setPrice(empty($data['PRICE_VAT']) ? NULL : $data['PRICE_VAT']);


			foreach((array) $data['CATEGORYTEXT'] as $categoryName)
			{
				if (!$category = $categoryRepository->findOneBy(array('name' => $categoryName)))
				{
					$category = new Category();
					$category->setName($categoryName);
				}

				$em->persist($category);

				if (!$product->getCategories()->contains($category))
				{
					$product->getCategories()->add($category);
				}
				if (!$category->getProducts()->contains($product))
				{
					$category->getProducts()->add($product);
				}
			}

			$em->flush();

			if (!($i++ % 100))
			{

				$em->clear();

				gc_collect_cycles();
				$output->writeln(sprintf('Memory usage: %dkB', memory_get_usage() / 1024));
			}

		}


	}
}
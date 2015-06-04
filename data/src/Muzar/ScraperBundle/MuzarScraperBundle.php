<?php

namespace Muzar\ScraperBundle;

use Muzar\ScraperBundle\Command\LinksCommand;
use Muzar\ScraperBundle\Command\ParseCommand;
use Muzar\ScraperBundle\LinkIterator;
use Muzar\ScraperBundle\HtmlParser;
use Symfony\Component\Console\Application;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class MuzarScraperBundle extends Bundle
{
	public function registerCommands(Application $application)
	{

		$em = $this->container->get('doctrine')->getManager();
		$goutte = $this->container->get('goutte');

		// Registrujeme si commandy rucne
		$application->add(new LinksCommand(
			$em,
			array(
				'hudebnibazar' => new LinkIterator\Hudebnibazar(),
				'midicz' => new LinkIterator\Midicz($goutte)
			)
		));

		$application->add(new ParseCommand(
			$em,
			$goutte,
			array(
				'hudebnibazar' => new HtmlParser\Hudebnibazar(),
				'midicz' => new HtmlParser\Midicz()
			)
		));

	}

}

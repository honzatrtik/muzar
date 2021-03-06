<?php
/**
 * Date: 06/03/15
 * Time: 12:45
 */

namespace Muzar\BazaarBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RebuildDataCommand extends ContainerAwareCommand
{

	protected function configure()
	{
		$this
			->setName('muzar:rebuild-data')
			->setDescription('Rebuilds all application data.');
    }

	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$application = $this->getApplication();

		$application->find('cache:clear')->run(new Input\StringInput('cache:clear'), $output);
		$application->find('redis:flush')->run(new Input\StringInput('redis:flush'), $output);
		$application->find('media-dir:clean')->run(new Input\StringInput('media-dir:clean'), $output);
		$application->find('doctrine:schema:drop')->run(new Input\StringInput('doctrine:schema:drop --force'), $output);
		$application->find('doctrine:schema:create')->run(new Input\StringInput('doctrine:schema:create'), $output);


		$input = new Input\StringInput('doctrine:fixtures:load');
		$input->setInteractive(FALSE);



		$application->find('doctrine:fixtures:load')->run($input, $output);
		$application->find('fos:elastica:populate')->run(new Input\StringInput('fos:elastica:populate'), $output);

		$application->find('muzar:scraper:links')->run(new Input\StringInput('muzar:scraper:links'), $output);
		$application->find('muzar:scraper:parse')->run(new Input\StringInput('muzar:scraper:parse -m 100'), $output);
		$application->find('muzar:import-scraped')->run(new Input\StringInput('muzar:import-scraped'), $output);

		$application->find('oauth:client:create')->run(new Input\StringInput('oauth:client:create --grant-type="password" -p web --custom-id="test"'), $output);
		$application->find('fos:user:create')->run(new Input\StringInput('fos:user:create test test@muzar.cz test'), $output);
		$application->find('redis:flush')->run(new Input\StringInput('redis:flush'), $output);
		$application->find('cache:clear')->run(new Input\StringInput('cache:clear'), $output);
	}
}
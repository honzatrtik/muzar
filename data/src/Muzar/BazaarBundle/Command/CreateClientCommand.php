<?php
/**
 * Date: 06/03/15
 * Time: 12:45
 */

namespace Muzar\BazaarBundle\Command;

use FOS\OAuthServerBundle\Model\ClientManagerInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Muzar\BazaarBundle\Entity\Client;

class CreateClientCommand extends ContainerAwareCommand
{
	/** @var  ClientManagerInterface */
	protected $clientManager;

	function __construct(ClientManagerInterface $clientManager)
	{
		$this->clientManager = $clientManager;
		parent::__construct();
	}


	protected function configure()
	{
		$this
			->setName('oauth:client:create')
			->setDescription('Creates a new client')
			->addArgument('name', InputArgument::REQUIRED, 'Sets the client name.')
			->addOption('public', 'p', InputOption::VALUE_NONE, 'Is public client?')
			->addOption('redirect-uri', null, InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY, 'Sets redirect uri for client. Use this option multiple times to set multiple redirect URIs.')
			->addOption('grant-type', null, InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY, 'Sets allowed grant type for client. Use this option multiple times to set multiple grant types.')
			->setHelp(<<<EOT
The <info>%command.name%</info>command creates a new client.

  <info>php %command.full_name% [--redirect-uri=...] [--grant-type=...] [--public] name</info>

EOT
        );
    }

	protected function execute(InputInterface $input, OutputInterface $output)
	{
		/** @var ClientManagerInterface $clientManager */
		$clientManager = $this->clientManager;

		/** @var Client $client */
		$client = $clientManager->createClient();

		$client->setName($input->getArgument('name'));
		$client->setRedirectUris($input->getOption('redirect-uri'));
		$client->setAllowedGrantTypes($input->getOption('grant-type'));

		if ($isPublic = $input->getOption('public'))
		{
			$client->setSecret('');
		}

		$clientManager->updateClient($client);

		$output->writeln(sprintf(
			'Added a new %s client with name <info>%s</info> and public id <info>%s</info>.',
			$isPublic ? '<info>public</info>' : '',
			$client->getName(),
			$client->getPublicId()
		));
	}
}
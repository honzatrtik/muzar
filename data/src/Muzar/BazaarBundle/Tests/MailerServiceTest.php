<?php

namespace Muzar\BazaarBundle\Tests;


use Muzar\BazaarBundle\Entity\Item;
use Muzar\BazaarBundle\MailerService;

class MailerServiceTest extends ApiTestCase
{

	/** @var  MailerService */
	protected $mailerService;

	protected function setUp()
	{
		parent::setUp();

		$engineMock = $this->getMockBuilder('\Symfony\Component\Templating\EngineInterface')
			->getMock();

//		$this->mailerService = new MailerService($this->getKernel()->getContainer()->get('mailer'), $engineMock);
		$this->mailerService = new MailerService(
			$this->getKernel()->getContainer()->get('mailer'),
			$this->getKernel()->getContainer()->get('templating')
		);
	}


	public function testCreateItemReplyMessage()
	{
		$item = new Item();
		$item->setName('Inzeratek');

		$message = $this->mailerService->createItemReplyMessage($item, 'test@mailinator.com', 'Test');
		$this->assertInstanceOf('\Swift_Message', $message);

		echo $message->toString();
	}


}

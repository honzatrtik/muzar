<?php
/**
 * Date: 09/04/15
 * Time: 16:21
 */

namespace Muzar\BazaarBundle;


use Html2Text\Html2Text;
use Muzar\BazaarBundle\Entity\Item;
use Symfony\Component\Templating\EngineInterface;


class MailerService
{
	/** @var  EngineInterface */
	protected $templating;

	/** @var  \Swift_Mailer */
	protected $mailer;

	function __construct(\Swift_Mailer $mailer, EngineInterface $templating)
	{
		$this->templating = $templating;
		$this->mailer = $mailer;
		$this->html2text = new Html2Text();
	}


	/**
	 * @param Item $item
	 * @param $senderEmail
	 * @param $senderMessage
	 * @param null $senderName
	 * @param null $senderPhone
	 * @return \Swift_Message
	 * @throws \Html2Text\Html2TextException
	 */
	public function createItemReplyMessage(Item $item, $senderEmail, $senderMessage, $senderName = NULL, $senderPhone = NULL)
	{
		/** @var \Swift_Message $message */
		$message = $this->mailer->createMessage();
		$message
			->setSubject(sprintf('muzar.cz - odpověď na inzerát "%s"', $item->getName()))
			->setSender($senderEmail, $senderName);

		$html = $this->templating->render('Emails/item-reply-message.html.twig', array(
			'item' => $item,
			'senderMessage' => $senderMessage,
			'senderEmail' => $senderEmail,
			'senderName' => $senderName,
			'senderPhone' => $senderPhone,
		));

		$message->setBody($html, 'text/html');
		$this->addPlainTextPart($message);


		return $message;
	}

	protected function addPlainTextPart(\Swift_Message $message)
	{
		$this->html2text->setHtml($message->getBody());
		return $message->addPart($this->html2text->getText(), 'text/plain');
	}

	/**
	 * @param \Swift_Message $message
	 * @return int
	 */
	public function send(\Swift_Message $message)
	{
		return $this->mailer->send($message);
	}

}
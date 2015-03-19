<?php
/**
 * Date: 25/11/13
 * Time: 17:14
 */

namespace Muzar\BazaarBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;


/**
 * @ORM\Entity(repositoryClass="Muzar\BazaarBundle\Entity\ContactRepository")
 * @ORM\Table(name="contact", indexes={
 * 		@ORM\Index(name="contact_email_idx",columns={"email"})
 * })
 * @ORM\HasLifecycleCallbacks
 *
 * @JMS\ExclusionPolicy("all")
 */
class Contact
{

	/**
	 * @ORM\Column(type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 * @JMS\Expose()
	 */
	protected $id;

	/**
	 * @ORM\Column(type="string")
	 * @JMS\Expose()
	 * @Assert\NotBlank()
	 * @Assert\Email()
	 */
	protected $email;

	/**
	 * @ORM\Column(type="string", nullable=true)
	 * @JMS\Expose()
	 * @Assert\NotBlank()
	 * @JMS\Groups({"elastica"})
	 */
	protected $name;

	/**
	 * @ORM\Column(type="string", nullable=true)
	 * @JMS\Expose()
	 *
	 */
	protected $phone;


	/**
	 * @ORM\Column(type="json_array")
	 * @JMS\Expose()
	 * @Assert\NotBlank()
	 * @JMS\Groups({"elastica"})
	 */
	protected $place = array();




	/**
	 * @param mixed $email
	 */
	public function setEmail($email)
	{
		$this->email = $email;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getEmail()
	{
		return $this->email;
	}

	/**
	 * @param mixed $id
	 */
	public function setId($id)
	{
		$this->id = $id;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * @param mixed $name
	 */
	public function setName($name)
	{
		$this->name = $name;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @param mixed $phone
	 */
	public function setPhone($phone)
	{
		$this->phone = $phone;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getPhone()
	{
		return $this->phone;
	}

	/**
	 * @param array $place
	 */
	public function setPlace(array $place)
	{
		$this->place = $place;
		return $this;
	}

	/**
	 * @return array
	 */
	public function getPlace()
	{
		return $this->place;
	}


	/**
	 * @Assert\Callback
	 */
	public function validatePlace(ExecutionContextInterface $context)
	{
		if ($place = $this->getPlace())
		{
			foreach(array('place_id', 'lat', 'lng', 'address_components') as $requiredKey)
			{
				if (empty($place[$requiredKey]))
				{
					$context
						->buildViolation('Město nebo obec není správně zadané.')
						->atPath('place')
						->addViolation();
					break;
				}
			}
		}
	}

}
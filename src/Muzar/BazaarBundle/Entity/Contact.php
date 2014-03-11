<?php
/**
 * Date: 25/11/13
 * Time: 17:14
 */

namespace Muzar\BazaarBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\ExecutionContextInterface;


/**
 * @ORM\Entity(repositoryClass="Muzar\BazaarBundle\Entity\ContactRepository")
 * @ORM\Table(name="contact", indexes={@ORM\Index(name="contact_email_idx",columns={"email"})})
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
	 * @ORM\Column(type="string")
	 * @JMS\Expose()
	 */
	protected $name;

	/**
	 * @ORM\Column(type="string")
	 * @JMS\Expose()
	 */
	protected $phone;



	/**
	 * @ORM\Column(type="float",  nullable=true)
	 * @JMS\Expose()
	 */
	protected $lat;

	/**
	 * @ORM\Column(type="float",  nullable=true)
	 * @JMS\Expose()
	 */
	protected $lng;

	/**
	 * @ORM\Column(type="string", nullable=true)
	 * @JMS\Expose()
	 */
	protected $address;

	/**
	 * @param mixed $address
	 */
	public function setAddress($address)
	{
		$this->address = $address;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getAddress()
	{
		return $this->address;
	}

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
	 * @param mixed $lat
	 */
	public function setLat($lat)
	{
		$this->lat = $lat;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getLat()
	{
		return $this->lat;
	}

	/**
	 * @param mixed $lng
	 */
	public function setLng($lng)
	{
		$this->lng = $lng;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getLng()
	{
		return $this->lng;
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







}
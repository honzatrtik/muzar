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
	 * @JMS\Exclude()
	 * @Assert\NotBlank()
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
	 * @JMS\VirtualProperty
	 * @JMS\SerializedName("address_components")
	 */
	public function getAddressComponents()
	{
		return isset($this->place['address_components'])
			? $this->place['address_components']
			: NULL;
	}

	/**
	 * @JMS\VirtualProperty
	 * @JMS\SerializedName("region")
	 * @JMS\Groups({"elastica"})
	 */
	public function getRegion()
	{
		return isset($this->place['address_components']['administrative_area_level_1'])
			? $this->place['address_components']['administrative_area_level_1']
			: NULL;
	}

	/**
	 * @JMS\VirtualProperty
	 * @JMS\SerializedName("district")
	 * @JMS\Groups({"elastica"})
	 */
	public function getDistrict()
	{
		return isset($this->place['address_components']['administrative_area_level_2'])
			? $this->place['address_components']['administrative_area_level_2']
			: NULL;
	}


	/**
	 * @JMS\VirtualProperty
	 * @JMS\SerializedName("city")
	 * @JMS\Groups({"elastica"})
	 */
	public function getCity()
	{
		return isset($this->place['address_components']['locality'])
			? $this->place['address_components']['locality']
			: NULL;
	}


	/**
	 * @JMS\VirtualProperty
	 * @JMS\SerializedName("country")
	 * @JMS\Groups({"elastica"})
	 */
	public function getCountry()
	{
		return isset($this->place['address_components']['country'])
			? $this->place['address_components']['country']
			: NULL;
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
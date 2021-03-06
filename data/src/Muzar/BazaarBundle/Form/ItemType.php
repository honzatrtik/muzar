<?php
/**
 * Date: 03/03/14
 * Time: 14:05
 */

namespace Muzar\BazaarBundle\Form;

use Muzar\BazaarBundle\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ItemType extends AbstractType
{

	/**
	 * @var Category[]
	 */
	protected $categories;

	/** @var boolean */
	protected $crsf;

	function __construct(array $categories, $crsf = FALSE)
	{
		$this->categories = $categories;
		$this->crsf = $crsf;
	}


	public function buildForm(FormBuilderInterface $builder, array $options)
	{

		$builder
			->add('name', 'text')
			->add('description', 'textarea', array(
				'required'  => false
			))
			->add('price', 'integer', array(
				'required'  => false
			))
			->add('negotiablePrice', 'checkbox', array(
				'required'  => false,
			))
			->add('allowSendingByMail', 'checkbox',array(
				'required'  => false,
			))
			->add('save', 'submit');

		$builder->add('category', 'entity', array(
			'empty_value' => '',
			'class' => 'Muzar\BazaarBundle\Entity\Category',
			'choices' => $this->categories,
			'property' => 'path',
		));

		$builder->add('contact', new ContactType());

	}


	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
			'data_class' => 'Muzar\BazaarBundle\Entity\Item',
			'csrf_protection'  => $this->crsf,
			'cascade_validation' => TRUE,
		));
	}

	public function getName()
	{
		return 'item';
	}

} 